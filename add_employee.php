<?php
include "config.php";
include "auth_check.php";

// Only Admin or HR can access
if (!(isAdmin() || isHR())) {
    echo "Access denied!";
    exit;
}

// Form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];
    $designation = $_POST['designation'];
    $salary = $_POST['salary'];
    $join_date = $_POST['join_date'];

    $stmt = $conn->prepare("INSERT INTO employees (name, email, phone, department, designation, salary, join_date)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $email, $phone, $department, $designation, $salary, $join_date);

    if ($stmt->execute()) {
        header("Location: employees.php?msg=Employee added successfully");
        exit;
    } else {
        $error = "Failed to add employee!";
    }
}

include "includes/header.php";
include "includes/sidebar.php";
?>

<div class="content-wrapper">
  <section class="content py-4">
    <div class="container">
      <h2 class="mb-4">Add New Employee</h2>

      <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

      <form method="POST" class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Department</label>
          <input type="text" name="department" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Designation</label>
          <input type="text" name="designation" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Salary</label>
          <input type="number" step="0.01" name="salary" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Join Date</label>
          <input type="date" name="join_date" class="form-control" required>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-success">Add Employee</button>
          <a href="employees.php" class="btn btn-secondary">Back</a>
        </div>
      </form>
    </div>
  </section>
</div>

<?php include "includes/footer.php"; ?>