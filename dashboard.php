<?php
include "config.php";
include "auth_check.php";

// Get logged in user info
$user = $_SESSION['user'];
$role = $user['role'];

// Count stats for admin/hr
if ($role == 'admin' || $role == 'hr') {
    $emp_count = $conn->query("SELECT COUNT(*) AS total FROM employees")->fetch_assoc()['total'];
    $leave_pending = $conn->query("SELECT COUNT(*) AS total FROM leaves WHERE status='Pending'")->fetch_assoc()['total'];
    $salary_unpaid = $conn->query("SELECT COUNT(*) AS total FROM salaries WHERE status='Unpaid'")->fetch_assoc()['total'];
}

include "includes/header.php";
include "includes/sidebar.php";
?>

<!-- Main Content Wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h2 class="mb-4">Welcome, <?= ucfirst($user['username']) ?> (<?= ucfirst($role) ?>)</h2>

            <?php if ($role == 'admin' || $role == 'hr'): ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5>Total Employees</h5>
                                <p class="fs-3"><?= $emp_count ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-dark">
                            <div class="card-body">
                                <h5>Pending Leaves</h5>
                                <p class="fs-3"><?= $leave_pending ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h5>Unpaid Salaries</h5>
                                <p class="fs-3"><?= $salary_unpaid ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($role == 'employee'): ?>
                <div class="alert alert-info mt-4 text-center">
                    <strong>Hello <?= $user['username'] ?>!</strong><br>
                    Use the menu below to manage your details.
                </div>
                <div class="text-center">
                    <a href="apply_leave.php" class="btn btn-outline-primary">Apply for Leave</a>
                    <a href="view_payslip.php" class="btn btn-outline-success">View Payslip</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>