<?php
include "config.php";
include "auth_check.php";

// Only admin can access user management
if (!isAdmin()) {
    echo "Access denied!";
    exit;
}

$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");

include "includes/header.php";
include "includes/sidebar.php";
?>

<div class="content-wrapper">
  <section class="content py-4">
    <div class="container">
      <h2 class="mb-4">User Management</h2>

      <a href="add_user.php" class="btn btn-primary mb-3">Add New User</a>

      <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
      <?php endif; ?>

      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>#ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Created At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $users->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= $row['username'] ?></td>
              <td><span class="badge bg-info text-dark"><?= ucfirst($row['role']) ?></span></td>
              <td><?= $row['created_at'] ?></td>
              <td>
                <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="delete_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                   onclick="return confirm('Are you sure you want to delete this user?');">
                  Delete
                </a>
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
