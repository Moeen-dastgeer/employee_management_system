<?php
include "config.php";
include "auth_check.php";

// Only Admin can access
if (!isAdmin()) {
    echo "Access denied!";
    exit;
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (username,password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $password, $role);

    if ($stmt->execute()) {
        header("Location: users.php?msg=User added successfully");
        exit;
    } else {
        $error = "Failed to add user!";
    }
}

include "includes/header.php";
include "includes/sidebar.php";
?>

<div class="content-wrapper">
  <section class="content py-4">
    <div class="container">
      <h2 class="mb-4">Add New User</h2>

      <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

      <form method="POST" class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Role</label>
          <select name="role" class="form-select" required>
            <option value="admin">Admin</option>
            <option value="hr">HR</option>
            <option value="employee">Employee</option>
          </select>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-success">Add User</button>
          <a href="users.php" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </section>
</div>

<?php include "includes/footer.php"; ?>
