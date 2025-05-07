<?php
include "config.php";
include "auth_check.php";

if (!isset($_GET['id'])) {
    echo "Payslip ID missing!";
    exit;
}

$id = $_GET['id'];

// Secure query using prepared statement
$stmt = $conn->prepare("SELECT s.*, e.name, e.designation 
                        FROM salaries s 
                        JOIN employees e ON s.employee_id = e.id 
                        WHERE s.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    echo "Payslip not found!";
    exit;
}

$salary = $result->fetch_assoc();

include "includes/header.php";
include "includes/sidebar.php";
?>

<div class="content-wrapper">
  <section class="content py-4">
    <div class="container">
      <div class="card">
        <div class="card-header bg-primary text-white">
          <h3 class="card-title">Payslip - <?= date('F Y') ?></h3>
        </div>
        <div class="card-body">
          <p><strong>Name:</strong> <?= $salary['name'] ?></p>
          <p><strong>Designation:</strong> <?= $salary['designation'] ?></p>
          <p><strong>Month:</strong> <?= $salary['month'] ?>/<?= $salary['year'] ?></p>

          <hr>

          <table class="table table-bordered">
            <tr><th>Basic Salary</th><td>Rs. <?= number_format($salary['basic'], 2) ?></td></tr>
            <tr><th>Allowances</th><td>Rs. <?= number_format($salary['allowances'], 2) ?></td></tr>
            <tr><th>Deductions</th><td>Rs. <?= number_format($salary['deductions'], 2) ?></td></tr>
            <tr class="table-success">
              <th><strong>Net Salary</strong></th>
              <td><strong>Rs. <?= number_format($salary['net_salary'], 2) ?></strong></td>
            </tr>
            <tr><th>Status</th><td><?= $salary['status'] ?></td></tr>
            <tr><th>Paid Date</th><td><?= $salary['paid_date'] ? $salary['paid_date'] : '-' ?></td></tr>
          </table>

          <div class="text-center mt-4">
            <button onclick="window.print()" class="btn btn-primary">Print</button>
            <a href="salary_list.php" class="btn btn-secondary">Back</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php include "includes/footer.php"; ?>