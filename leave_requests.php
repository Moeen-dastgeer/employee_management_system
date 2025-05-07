<?php
include "config.php";
include "auth_check.php";

// Only Admin or HR can view leave requests
if (!(isAdmin() || isHR())) {
    echo "Access denied!";
    exit;
}

$requests = $conn->query("SELECT l.*, e.name FROM leaves l 
                          JOIN employees e ON l.employee_id = e.id 
                          ORDER BY l.request_date DESC");

include "includes/header.php";
include "includes/sidebar.php";
?>

<div class="content-wrapper">
  <section class="content py-4">
    <div class="container">
      <h2 class="mb-4">Leave Requests</h2>

      <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success text-center">
          <?= htmlspecialchars($_GET['msg']) ?>
        </div>
      <?php endif; ?>

      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>Employee</th>
            <th>From</th>
            <th>To</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $requests->fetch_assoc()): ?>
            <tr>
              <td><?= $row['name'] ?></td>
              <td><?= $row['from_date'] ?></td>
              <td><?= $row['to_date'] ?></td>
              <td><?= $row['reason'] ?></td>
              <td>
                <span class="badge 
                    <?= $row['status'] === 'Approved' ? 'bg-success' : 
                       ($row['status'] === 'Rejected' ? 'bg-danger' : 'bg-warning text-dark') ?>">
                  <?= $row['status'] ?>
                </span>
              </td>
              <td>
                <?php if ($row['status'] === 'Pending'): ?>
                  <a href="update_leave_status.php?id=<?= $row['id'] ?>&action=approve" class="btn btn-sm btn-success">Approve</a>
                  <a href="update_leave_status.php?id=<?= $row['id'] ?>&action=reject" class="btn btn-sm btn-danger">Reject</a>
                <?php else: ?>
                  <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
  </section>
</div>

<?php include "includes/footer.php"; ?>