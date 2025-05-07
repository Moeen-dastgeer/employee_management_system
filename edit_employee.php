<?php
include "config.php";
include "auth_check.php";

// Only Admin or HR can edit employee
if (!(isAdmin() || isHR())) {
    echo "Access denied!";
    exit;
}

// Get employee ID from query
if (!isset($_GET['id'])) {
    echo "Employee ID not provided!";
    exit;
}

$id = intval($_GET['id']);

// Fetch employee data
$stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

if (!$employee) {
    echo "Employee not found!";
    exit;
}

// Update form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];
    $designation = $_POST['designation'];
    $salary = $_POST['salary'];
    $join_date = $_POST['join_date'];

    $update = $conn->prepare("UPDATE employees SET name=?, email=?, phone=?, department=?, designation=?, salary=?, join_date=? WHERE id=?");
    $update->bind_param("sssssdsi", $name, $email, $phone, $department, $designation, $salary, $join_date, $id);
    if ($update->execute()) {
        header("Location: employees.php?msg=Employee updated successfully");
        exit;
    } else {
        $error = "Update failed!";
    }
}

include "includes/header.php";
include "includes/sidebar.php";
?>

<div class="content-wrapper">
  <section class="content py-4">
    <div class="container">
      <h2>Edit Employee</h2>

      <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" value="<?= $employee['name'] ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="<?= $employee['email'] ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" class="form-control" value="<?= $employee['phone'] ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Department</label>
          <input type="text" name="department" class="form-control" value="<?= $employee['department'] ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Designation</label>
          <input type="text" name="designation" class="form-control" value="<?= $employee['designation'] ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Salary</label>
          <input type="number" step="0.01" name="salary" class="form-control" value="<?= $employee['salary'] ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Join Date</label>
          <input type="date" name="join_date" class="form-control" value="<?= $employee['join_date'] ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update Employee</button>
        <a href="employees.php" class="btn btn-secondary">Cancel</a>
      </form>
    </div>
  </section>
</div>

<?php include "includes/footer.php"; ?>