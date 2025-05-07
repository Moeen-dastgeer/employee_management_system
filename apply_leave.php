<?php
include "config.php";
include "auth_check.php";

// Only employees can apply for leave
if (!isEmployee()) {
    echo "Access denied!";
    exit;
}

$eid = $_SESSION['user']['id'];

// Form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $from = $_POST['from_date'];
    $to = $_POST['to_date'];
    $reason = $_POST['reason'];

    $stmt = $conn->prepare("INSERT INTO leaves (employee_id, from_date, to_date, reason) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $eid, $from, $to, $reason);

    if ($stmt->execute()) {
        $msg = "Leave request submitted!";
    } else {
        $error = "Failed to submit leave!";
    }
}

include "includes/header.php";
include "includes/sidebar.php";
?>

<div class="content-wrapper">
  <section class="content py-4">
    <div class="container">
      <h2 class="mb-4">Apply for Leave</h2>

      <?php if (isset($msg)): ?>
        <div class="alert alert-success"><?= $msg ?></div>
      <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <form method="POST" class="row g-3">
        <div class="col-md-6">
          <label class="form-label">From Date</label>
          <input type="date" name="from_date" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">To Date</label>
          <input type="date" name="to_date" class="form-control" required>
        </div>
        <div class="col-12">
          <label class="form-label">Reason</label>
          <textarea name="reason" class="form-control" required placeholder="Write your reason..."></textarea>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary">Submit Leave Request</button>
          <a href="dashboard.php" class="btn btn-secondary">Back</a>
        </div>
      </form>
    </div>
  </section>
</div>

<?php include "includes/footer.php"; ?>