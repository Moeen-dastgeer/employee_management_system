<?php $current = basename($_SERVER['PHP_SELF']); ?>
<?php
if (!isset($role)) {
    $role = $_SESSION['user']['role'] ?? '';
}
?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="#" class="brand-link">
    <span class="brand-text font-weight-light">EMS System</span>
  </a>

  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

        <li class="nav-item">
          <a href="dashboard.php" class="nav-link <?= $current == 'dashboard.php' ? 'active' : '' ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p>
          </a>
        </li>

        <?php if ($role == 'admin' || $role == 'hr'): ?>
          <li class="nav-item">
            <a href="employees.php" class="nav-link <?= $current == 'employees.php' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-users"></i><p>Employees</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="mark_attendance.php" class="nav-link <?= $current == 'mark_attendance.php' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-calendar-check"></i><p>Mark Attendance</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="attendance.php" class="nav-link <?= $current == 'attendance.php' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-calendar-alt"></i><p>View Attendance</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="salary_list.php" class="nav-link <?= $current == 'salary_list.php' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-money-bill"></i><p>Salaries</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="leave_requests.php" class="nav-link <?= $current == 'leave_requests.php' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-envelope-open-text"></i><p>Leave Requests</p>
            </a>
          </li>
        <?php endif; ?>
        <?php if ($role == 'admin'): ?>
                <li class="nav-item">
                <a href="users.php" class="nav-link <?= $current == 'users.php' ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-user-cog"></i><p>User Management</p>
                </a>
                </li>
        <?php endif; ?>

        <?php if ($role == 'employee'): ?>
          <li class="nav-item">
            <a href="apply_leave.php" class="nav-link <?= $current == 'apply_leave.php' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-paper-plane"></i><p>Apply Leave</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="view_payslip.php" class="nav-link <?= $current == 'view_payslip.php' ? 'active' : '' ?>">
              <i class="nav-icon fas fa-file-invoice-dollar"></i><p>View Payslip</p>
            </a>
          </li>
        <?php endif; ?>

        <li class="nav-item">
          <a href="logout.php" class="nav-link">
            <i class="nav-icon fas fa-sign-out-alt"></i><p>Logout</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>
