<?php
include "config.php";
include "auth_check.php";

// Only Admin or HR can access this
if (!(isAdmin() || isHR())) {
    echo "Access denied!";
    exit;
}

// Check for salary ID
if (!isset($_GET['id'])) {
    echo "Invalid request!";
    exit;
}

$salary_id = intval($_GET['id']);

// Fetch current salary info
$stmt = $conn->prepare("SELECT s.*, e.name FROM salaries s JOIN employees e ON s.employee_id = e.id WHERE s.id = ?");
$stmt->bind_param("i", $salary_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    echo "Salary record not found!";
    exit;
}

$salary = $result->fetch_assoc();

// Handle update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $allowances = floatval($_POST['allowances']);
    $deductions = floatval($_POST['deductions']);
    $net_salary = $salary['basic'] + $allowances - $deductions;

    $update = $conn->prepare("UPDATE salaries SET allowances=?, deductions=?, net_salary=? WHERE id=?");
    $update->bind_param("dddi", $allowances, $deductions, $net_salary, $salary_id);

    if ($update->execute()) {
        header("Location: salary_list.php?msg=Salary updated successfully");
        exit;
    } else {
        $error = "Failed to update salary!";
    }
}

include "includes/header.php";
include "includes/sidebar.php";
?>

<div class="content-wrapper">
  <section class="content py-4">
    <div class="container">
      <h2>Edit Salary - <?= $salary['name'] ?> (<?= $salary['month'] ?>/<?= $salary['year'] ?>)</h2>

      <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

      <form method="POST" class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Basic Salary</label>
          <input type="text" class="form-control" value="<?= $salary['basic'] ?>" disabled>
        </div>
        <div class="col-md-6">
          <label class="form-label">Allowances</label>
          <input type="number" step="0.01" name="allowances" class="form-control" value="<?= $salary['allowances'] ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Deductions</label>
          <input type="number" step="0.01" name="deductions" class="form-control" value="<?= $salary['deductions'] ?>" required>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary">Update Salary</button>
          <a href="salary_list.php" class="btn btn-secondary">Back</a>
        </div>
      </form>
    </div>
  </section>
</div>

<?php include "includes/footer.php"; ?>