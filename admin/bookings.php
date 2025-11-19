<?php
require_once '../includes/functions.php';
requireAdmin();

$db = new Database();
$conn = $db->getConnection();

// Get all bookings with slot information
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? sanitize($_GET['status']) : '';

$query = "SELECT pb.*, ps.slot_number, ps.slot_floor 
          FROM parking_bookings pb 
          JOIN parking_slots ps ON pb.slot_id = ps.id 
          WHERE 1=1";

if (!empty($search)) {
    $query .= " AND (pb.booking_number LIKE '%$search%' OR pb.vehicle_number LIKE '%$search%' OR pb.owner_name LIKE '%$search%' OR pb.owner_phone LIKE '%$search%')";
}

if (!empty($status_filter)) {
    $query .= " AND pb.status = '$status_filter'";
}

$query .= " ORDER BY pb.created_at DESC";

$bookings = [];
$result = $db->query($query);
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

$page_title = 'All Bookings';
include '../includes/header.php';
?>

<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="page-header">
            <h1>All Bookings</h1>
            <a href="new-booking.php" class="btn btn-primary">New Booking</a>
        </div>
        
        <div class="filters">
            <form method="GET" class="filter-form">
                <input type="text" name="search" placeholder="Search by booking#, vehicle#, owner..." 
                       value="<?php echo htmlspecialchars($search); ?>" class="form-control">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="parked" <?php echo $status_filter == 'parked' ? 'selected' : ''; ?>>Parked</option>
                    <option value="exited" <?php echo $status_filter == 'exited' ? 'selected' : ''; ?>>Exited</option>
                    <option value="cancelled" <?php echo $status_filter == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                </select>
                <button type="submit" class="btn btn-secondary">Filter</button>
                <a href="bookings.php" class="btn btn-light">Reset</a>
            </form>
        </div>
        
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Booking #</th>
                        <th>Vehicle #</th>
                        <th>Owner</th>
                        <th>Phone</th>
                        <th>Slot</th>
                        <th>Entry Time</th>
                        <th>Exit Time</th>
                        <th>Charge</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bookings)): ?>
                    <tr>
                        <td colspan="10" class="text-center">No bookings found</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['booking_number']); ?></td>
                        <td><?php echo htmlspecialchars($booking['vehicle_number']); ?></td>
                        <td><?php echo htmlspecialchars($booking['owner_name']); ?></td>
                        <td><?php echo htmlspecialchars($booking['owner_phone']); ?></td>
                        <td><?php echo htmlspecialchars($booking['slot_number']); ?></td>
                        <td><?php echo formatDate($booking['entry_time']); ?></td>
                        <td><?php echo $booking['exit_time'] ? formatDate($booking['exit_time']) : '-'; ?></td>
                        <td><?php echo $booking['parking_charge'] ? '₹' . number_format($booking['parking_charge'], 2) : '-'; ?></td>
                        <td>
                            <span class="badge badge-<?php echo $booking['status']; ?>">
                                <?php echo ucfirst($booking['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="view-booking.php?id=<?php echo $booking['id']; ?>" class="btn-sm btn-info">View</a>
                            <?php if ($booking['status'] == 'parked'): ?>
                            <a href="exit-vehicle.php?id=<?php echo $booking['id']; ?>" class="btn-sm btn-success">Exit</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
$db->closeConnection();
include '../includes/footer.php'; 
?>
