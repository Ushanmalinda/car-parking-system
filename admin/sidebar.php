<div class="sidebar">
    <div class="sidebar-header">
        <h2>Admin Panel</h2>
    </div>
    <nav class="sidebar-nav">
        <a href="dashboard.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
            <span>📊</span> Dashboard
        </a>
        <a href="parking-slots.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'parking-slots.php' ? 'active' : ''; ?>">
            <span>🅿️</span> Parking Slots
        </a>
        <a href="new-booking.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'new-booking.php' ? 'active' : ''; ?>">
            <span>➕</span> New Booking
        </a>
        <a href="bookings.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'bookings.php' ? 'active' : ''; ?>">
            <span>📋</span> All Bookings
        </a>
        <a href="vehicle-categories.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'vehicle-categories.php' ? 'active' : ''; ?>">
            <span>🚗</span> Vehicle Categories
        </a>
        <a href="reports.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">
            <span>📈</span> Reports
        </a>
        <a href="bug-reports.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'bug-reports.php' ? 'active' : ''; ?>">
            <span>🐛</span> Bug Reports
        </a>
        <?php if (isSystemAdmin()): ?>
        <a href="support-settings.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'support-settings.php' ? 'active' : ''; ?>" style="background: rgba(156, 39, 176, 0.1);">
            <span>🛠️</span> Support Settings
        </a>
        <?php endif; ?>
        <a href="users.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>">
            <span>👥</span> Users
        </a>
        <a href="settings.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
            <span>⚙️</span> Settings
        </a>
        <a href="../logout.php" class="nav-item">
            <span>🚪</span> Logout
        </a>
    </nav>
</div>
