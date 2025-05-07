<?php
include "config.php";
include "auth_check.php";

if (!(isAdmin() || isHR())) {
    echo "Access denied!";
    exit;
}

$employees = $conn->query("SELECT * FROM employees");

include "includes/header.php";
include "includes/sidebar.php";
?>

<div class="content-wrapper">
  <section class="content py-4">
    <div class="container">
      <h2 class="mb-4">Employee List</h2>

      <a href="add_employee.php" class="btn btn-primary mb-3">Add New Employee</a>

      <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
      <?php endif; ?>

      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>#ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Department</th>
            <th>Designation</th>
            <th>Salary</th>
            <th>Join Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $employees->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= $row['name'] ?></td>
              <td><?= $row['email'] ?></td>
              <td><?= $row['phone'] ?></td>
              <td><?= $row['department'] ?></td>
              <td><?= $row['designation'] ?></td>
              <td>Rs. <?= number_format($row['salary'], 2) ?></td>
              <td><?= $row['join_date'] ?></td>
              <td>
                <a href="edit_employee.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="delete_employee.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <a href="dashboard.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
    </div>
  </section>
</div>

<?php include "includes/footer.php"; ?>