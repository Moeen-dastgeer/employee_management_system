<?php
include "config.php";
include "auth_check.php";

if (!isAdmin() && !isHR()) {
    echo "Access denied!";
    exit;
}

$month = date('m');
$year = date('Y');

// Get all employees
$employees = $conn->query("SELECT * FROM employees");

include "includes/header.php";
include "includes/sidebar.php";
?>

<div class="content-wrapper">
  <section class="content py-4">
    <div class="container">
      <h2 class="mb-4 text-center">Salary Sheet - <?= date('F Y') ?></h2>

      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>Employee</th>
            <th>Basic</th>
            <th>Allowances</th>
            <th>Deductions</th>
            <th>Net Salary</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($emp = $employees->fetch_assoc()):

          $eid = $emp['id'];

          // Check if salary record exists
          $stmt = $conn->prepare("SELECT * FROM salaries WHERE employee_id = ? AND month = ? AND year = ?");
          $stmt->bind_param("isi", $eid, $month, $year);
          $stmt->execute();
          $result = $stmt->get_result();
          $sal = $result->fetch_assoc();

          // If no record, insert default
          if (!$sal) {
              $basic = $emp['salary'];
              $allowances = 0;
              $deductions = 0;
              $net = $basic + $allowances - $deductions;

              $insert = $conn->prepare("INSERT INTO salaries (employee_id, month, year, basic, allowances, deductions, net_salary) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?)");
              $insert->bind_param("isidddd", $eid, $month, $year, $basic, $allowances, $deductions, $net);
              $insert->execute();

              // Re-fetch new record
              $stmt->execute();
              $result = $stmt->get_result();
              $sal = $result->fetch_assoc();
          }
        ?>
          <tr>
            <td><?= $emp['name'] ?></td>
            <td>Rs. <?= number_format($sal['basic'], 2) ?></td>
            <td>Rs. <?= number_format($sal['allowances'], 2) ?></td>
            <td>Rs. <?= number_format($sal['deductions'], 2) ?></td>
            <td><strong>Rs. <?= number_format($sal['net_salary'], 2) ?></strong></td>
            <td>
              <span class="badge <?= $sal['status'] == 'Paid' ? 'bg-success' : 'bg-danger' ?>">
                <?= $sal['status'] ?>
              </span>
            </td>
            <td>
              <?php if ($sal['status'] == 'Unpaid'): ?>
                <a href="pay_salary.php?id=<?= $sal['id'] ?>" class="btn btn-sm btn-success">Mark Paid</a>
              <?php endif; ?>
              <a href="view_payslip.php?id=<?= $sal['id'] ?>" target="_blank" class="btn btn-sm btn-primary">View Payslip</a>
              <a href="edit_salary.php?id=<?= $sal['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>

      <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </div>
  </section>
</div>

<?php include "includes/footer.php"; ?>