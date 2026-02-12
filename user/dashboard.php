<?php
require_once '../includes/functions.php';
requireLogin();

$db = new Database();
$stats = getDashboardStats($db);

// Get recent bookings for this user (if staff role)
$recent_bookings = [];
$result = $db->query("SELECT pb.*, ps.slot_number FROM parking_bookings pb 
                      JOIN parking_slots ps ON pb.slot_id = ps.id 
                      ORDER BY pb.created_at DESC LIMIT 10");
while ($row = $result->fetch_assoc()) {
    $recent_bookings[] = $row;
}

$page_title = 'Dashboard';
include '../includes/header.php';
?>

<div class="dashboard-wrapper">
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Staff Panel</h2>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="nav-item active">
                <span>📊</span> Dashboard
            </a>
            <a href="../logout.php" class="nav-item">
                <span>🚪</span> Logout
            </a>
        </nav>
    </div>
    
    <div class="main-content">
        <div class="page-header">
            <h1>Dashboard</h1>
            <div class="user-info">
                Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <span>🅿️</span>
                </div>
                <div class="stat-details">
                    <h3><?php echo $stats['total_slots']; ?></h3>
                    <p>Total Slots</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon green">
                    <span>✓</span>
                </div>
                <div class="stat-details">
                    <h3><?php echo $stats['available_slots']; ?></h3>
                    <p>Available Slots</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon red">
                    <span>⊗</span>
                </div>
                <div class="stat-details">
                    <h3><?php echo $stats['occupied_slots']; ?></h3>
                    <p>Occupied Slots</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon orange">
                    <span>📋</span>
                </div>
                <div class="stat-details">
                    <h3><?php echo $stats['today_bookings']; ?></h3>
                    <p>Today's Bookings</p>
                </div>
            </div>
        </div>
        
        <div class="recent-section">
            <h2>Recent Bookings</h2>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Booking #</th>
                            <th>Vehicle Number</th>
                            <th>Slot</th>
                            <th>Owner Name</th>
                            <th>Entry Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recent_bookings)): ?>
                        <tr>
                            <td colspan="6" class="text-center">No bookings found</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($recent_bookings as $booking): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['booking_number']); ?></td>
                            <td><?php echo htmlspecialchars($booking['vehicle_number']); ?></td>
                            <td><?php echo htmlspecialchars($booking['slot_number']); ?></td>
                            <td><?php echo htmlspecialchars($booking['owner_name']); ?></td>
                            <td><?php echo formatDate($booking['entry_time']); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $booking['status']; ?>">
                                    <?php echo ucfirst($booking['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php 
$db->closeConnection();
include '../includes/footer.php'; 
?>
