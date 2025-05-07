<?php
include "config.php";
include "auth_check.php";

// Only Admin or HR can access this page
if (!(isAdmin() || isHR())) {
    echo "Access denied!";
    exit;
}

// Apply date filter if present
$where = "";
if (isset($_GET['from']) && isset($_GET['to'])) {
    $from = $_GET['from'];
    $to = $_GET['to'];
    $where = "WHERE a.date BETWEEN '$from' AND '$to'";
}

$query = "SELECT a.*, e.name 
          FROM attendance a 
          JOIN employees e ON a.employee_id = e.id 
          $where 
          ORDER BY a.date DESC";

$result = $conn->query($query);

include "includes/header.php";
include "includes/sidebar.php";
?>

<div class="content-wrapper">
  <section class="content py-4">
    <div class="container">
      <h2 class="mb-4">Attendance Records</h2>

      <!-- Filter Form -->
      <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
          <label>From Date</label>
          <input type="date" name="from" class="form-control" value="<?= $_GET['from'] ?? '' ?>" required>
        </div>
        <div class="col-md-4">
          <label>To Date</label>
          <input type="date" name="to" class="form-control" value="<?= $_GET['to'] ?? '' ?>" required>
        </div>
        <div class="col-md-4 align-self-end">
          <button type="submit" class="btn btn-primary">Filter</button>
          <a href="attendance.php" class="btn btn-secondary">Reset</a>
        </div>
      </form>

      <!-- Table -->
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>Date</th>
            <th>Employee</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $row['date'] ?></td>
                <td><?= $row['name'] ?></td>
                <td>
                  <span class="badge 
                    <?= $row['status'] == 'Present' ? 'bg-success' : ($row['status'] == 'Leave' ? 'bg-warning text-dark' : 'bg-danger') ?>">
                    <?= $row['status'] ?>
                  </span>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="3" class="text-center">No attendance records found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>

      <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </div>
  </section>
</div>

<?php include "includes/footer.php"; ?>