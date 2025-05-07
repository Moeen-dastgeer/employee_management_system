<?php
include "config.php";
include "auth_check.php";

if (!(isAdmin() || isHR())) {
    echo "Access denied!";
    exit;
}

$today = date('Y-m-d');
$employees = $conn->query("SELECT * FROM employees");

// Fetch attendance statuses
$attendance_today = [];
$res = $conn->query("SELECT employee_id, status FROM attendance WHERE date = '$today'");
while ($row = $res->fetch_assoc()) {
    $attendance_today[$row['employee_id']] = $row['status'];
}

include "includes/header.php";
include "includes/sidebar.php";
?>

<div class="content-wrapper">
  <section class="content py-4">
    <div class="container">
      <h2 class="mb-4">Mark Attendance - <?= $today ?></h2>
      <p>You can update attendance anytime by clicking a new status.</p>

      <table class="table table-bordered">
        <thead class="table-dark">
          <tr>
            <th>Employee</th>
            <th>Action</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($emp = $employees->fetch_assoc()): 
            $id = $emp['id'];
            $status = $attendance_today[$id] ?? 'Not Marked';
        ?>
          <tr id="row-<?= $id ?>">
            <td><?= $emp['name'] ?></td>
            <td>
              <button class="btn btn-success btn-sm" onclick="markAttendance(<?= $id ?>, 'Present')">Present</button>
              <button class="btn btn-danger btn-sm" onclick="markAttendance(<?= $id ?>, 'Absent')">Absent</button>
              <button class="btn btn-warning btn-sm" onclick="markAttendance(<?= $id ?>, 'Late')">Late</button>
            </td>
            <td>
              <span id="status-<?= $id ?>" class="status-updated 
                <?= $status === 'Present' ? 'text-success' : ($status === 'Absent' ? 'text-danger' : ($status === 'Late' ? 'text-warning' : 'text-muted')) ?>">
                <?= $status ?>
              </span>
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

<script>
function markAttendance(empId, status) {
    const date = "<?= $today ?>";

    fetch('submit_attendance_ajax.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `employee_id=${empId}&status=${status}&date=${date}`
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('status-' + empId).innerText = status;
        let className = 'status-updated ';
        className += (status === 'Present') ? 'text-success' :
                     (status === 'Absent') ? 'text-danger' :
                     (status === 'Late') ? 'text-warning' : 'text-muted';
        document.getElementById('status-' + empId).className = className;
    })
    .catch(err => {
        alert("Error: " + err);
    });
}
</script>
