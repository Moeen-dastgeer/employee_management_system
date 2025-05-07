<?php
include "config.php";
include "auth_check.php";

// Only admin can access
if (!isAdmin()) {
    echo "Access denied!";
    exit;
}

// Check ID
if (!isset($_GET['id'])) {
    echo "User ID missing!";
    exit;
}

$id = intval($_GET['id']);

// Fetch user
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    echo "User not found!";
    exit;
}

$user = $result->fetch_assoc();

// Handle form update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $role = $_POST['role'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET username=?, password=?, role=? WHERE id=?");
        $stmt->bind_param("ssssi", $username, $password, $role, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username=?, role=? WHERE id=?");
        $stmt->bind_param("sssi", $username, $role, $id);
    }

    if ($stmt->execute()) {
        header("Location: users.php?msg=User updated successfully");
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
      <h2 class="mb-4">Edit User</h2>

      <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

      <form method="POST" class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" value="<?= $user['username'] ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Password (Leave blank to keep unchanged)</label>
          <input type="password" name="password" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Role</label>
          <select name="role" class="form-select" required>
            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="hr" <?= $user['role'] == 'hr' ? 'selected' : '' ?>>HR</option>
            <option value="employee" <?= $user['role'] == 'employee' ? 'selected' : '' ?>>Employee</option>
          </select>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary">Update User</button>
          <a href="users.php" class="btn btn-secondary">Back</a>
        </div>
      </form>
    </div>
  </section>
</div>

<?php include "includes/footer.php"; ?>
