<?php
require_once '../includes/functions.php';
requireAdmin();

$db = new Database();

// Get date filters
$from_date = isset($_GET['from_date']) ? sanitize($_GET['from_date']) : date('Y-m-01');
$to_date = isset($_GET['to_date']) ? sanitize($_GET['to_date']) : date('Y-m-d');

// Get report data
$total_bookings = 0;
$total_revenue = 0;
$two_wheeler_count = 0;
$four_wheeler_count = 0;

$result = $db->query("SELECT COUNT(*) as count, COALESCE(SUM(parking_charge), 0) as revenue 
                      FROM parking_bookings 
                      WHERE DATE(entry_time) BETWEEN '$from_date' AND '$to_date' AND status = 'exited'");
$data = $result->fetch_assoc();
$total_bookings = $data['count'];
$total_revenue = $data['revenue'];

$result = $db->query("SELECT COUNT(*) as count FROM parking_bookings 
                      WHERE vehicle_type = 'two_wheeler' AND DATE(entry_time) BETWEEN '$from_date' AND '$to_date'");
$two_wheeler_count = $result->fetch_assoc()['count'];

$result = $db->query("SELECT COUNT(*) as count FROM parking_bookings 
                      WHERE vehicle_type = 'four_wheeler' AND DATE(entry_time) BETWEEN '$from_date' AND '$to_date'");
$four_wheeler_count = $result->fetch_assoc()['count'];

// Get daily revenue
$daily_revenue = [];
$result = $db->query("SELECT DATE(entry_time) as date, COUNT(*) as bookings, COALESCE(SUM(parking_charge), 0) as revenue 
                      FROM parking_bookings 
                      WHERE DATE(entry_time) BETWEEN '$from_date' AND '$to_date' AND status = 'exited'
                      GROUP BY DATE(entry_time)
                      ORDER BY date DESC");
while ($row = $result->fetch_assoc()) {
    $daily_revenue[] = $row;
}

$page_title = 'Reports';
include '../includes/header.php';
?>

<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="page-header">
            <h1>Reports & Analytics</h1>
        </div>
        
        <div class="filters">
            <form method="GET" class="filter-form">
                <label>From Date:</label>
                <input type="date" name="from_date" value="<?php echo $from_date; ?>" class="form-control">
                <label>To Date:</label>
                <input type="date" name="to_date" value="<?php echo $to_date; ?>" class="form-control">
                <button type="submit" class="btn btn-primary">Generate Report</button>
            </form>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <span>📋</span>
                </div>
                <div class="stat-details">
                    <h3><?php echo $total_bookings; ?></h3>
                    <p>Total Bookings</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon green">
                    <span>💰</span>
                </div>
                <div class="stat-details">
                    <h3>₹<?php echo number_format($total_revenue, 2); ?></h3>
                    <p>Total Revenue</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon orange">
                    <span>🏍️</span>
                </div>
                <div class="stat-details">
                    <h3><?php echo $two_wheeler_count; ?></h3>
                    <p>Two Wheelers</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon purple">
                    <span>🚗</span>
                </div>
                <div class="stat-details">
                    <h3><?php echo $four_wheeler_count; ?></h3>
                    <p>Four Wheelers</p>
                </div>
            </div>
        </div>
        
        <div class="report-section">
            <h2>Daily Revenue Report</h2>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Total Bookings</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($daily_revenue)): ?>
                        <tr>
                            <td colspan="3" class="text-center">No data available for selected period</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($daily_revenue as $row): ?>
                        <tr>
                            <td><?php echo date('d M Y', strtotime($row['date'])); ?></td>
                            <td><?php echo $row['bookings']; ?></td>
                            <td>₹<?php echo number_format($row['revenue'], 2); ?></td>
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
