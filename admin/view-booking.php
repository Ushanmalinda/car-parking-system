<?php
require_once '../includes/functions.php';
requireAdmin();

$db = new Database();
$conn = $db->getConnection();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get booking details
$stmt = $conn->prepare("SELECT pb.*, ps.slot_number, ps.slot_floor, vc.category_name, vc.rate_per_hour 
                        FROM parking_bookings pb 
                        JOIN parking_slots ps ON pb.slot_id = ps.id 
                        LEFT JOIN vehicle_categories vc ON pb.vehicle_category_id = vc.id 
                        WHERE pb.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$booking) {
    setFlashMessage('error', 'Booking not found');
    header('Location: bookings.php');
    exit();
}

// Get payment details if exists
$payment = null;
if ($booking['status'] == 'exited') {
    $stmt = $conn->prepare("SELECT * FROM payments WHERE booking_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $payment = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

$page_title = 'View Booking';
include '../includes/header.php';
?>

<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="page-header">
            <h1>Booking Details</h1>
            <div>
                <a href="bookings.php" class="btn btn-secondary">Back</a>
                <?php if ($booking['status'] == 'parked'): ?>
                <a href="exit-vehicle.php?id=<?php echo $booking['id']; ?>" class="btn btn-success">Exit Vehicle</a>
                <?php endif; ?>
                <?php if ($booking['status'] == 'exited'): ?>
                <button onclick="window.print()" class="btn btn-primary">Print Receipt</button>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="booking-details-container">
            <div class="detail-card">
                <h3>Booking Information</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="label">Booking Number:</span>
                        <span class="value"><?php echo htmlspecialchars($booking['booking_number']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Status:</span>
                        <span class="badge badge-<?php echo $booking['status']; ?>">
                            <?php echo ucfirst($booking['status']); ?>
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Created On:</span>
                        <span class="value"><?php echo formatDate($booking['created_at']); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="detail-card">
                <h3>Vehicle Information</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="label">Vehicle Number:</span>
                        <span class="value"><?php echo htmlspecialchars($booking['vehicle_number']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Vehicle Type:</span>
                        <span class="value"><?php echo ucwords(str_replace('_', ' ', $booking['vehicle_type'])); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Category:</span>
                        <span class="value"><?php echo htmlspecialchars($booking['category_name'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Company:</span>
                        <span class="value"><?php echo htmlspecialchars($booking['vehicle_company'] ?? 'N/A'); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="detail-card">
                <h3>Owner Information</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="label">Owner Name:</span>
                        <span class="value"><?php echo htmlspecialchars($booking['owner_name']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Phone:</span>
                        <span class="value"><?php echo htmlspecialchars($booking['owner_phone']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Email:</span>
                        <span class="value"><?php echo htmlspecialchars($booking['owner_email'] ?? 'N/A'); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="detail-card">
                <h3>Parking Information</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="label">Parking Slot:</span>
                        <span class="value"><?php echo htmlspecialchars($booking['slot_number']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Floor:</span>
                        <span class="value"><?php echo htmlspecialchars($booking['slot_floor']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Entry Time:</span>
                        <span class="value"><?php echo formatDate($booking['entry_time']); ?></span>
                    </div>
                    <?php if ($booking['exit_time']): ?>
                    <div class="detail-item">
                        <span class="label">Exit Time:</span>
                        <span class="value"><?php echo formatDate($booking['exit_time']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if ($booking['status'] == 'exited' && $payment): ?>
            <div class="detail-card payment-card">
                <h3>Payment Information</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="label">Parking Charge:</span>
                        <span class="value amount"><?php echo formatCurrency($booking['parking_charge'], $db); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Payment Method:</span>
                        <span class="value"><?php echo ucfirst($payment['payment_method']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Payment Status:</span>
                        <span class="badge badge-<?php echo $payment['payment_status']; ?>">
                            <?php echo ucfirst($payment['payment_status']); ?>
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Payment Date:</span>
                        <span class="value"><?php echo formatDate($payment['payment_date']); ?></span>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if ($booking['remarks']): ?>
            <div class="detail-card">
                <h3>Remarks</h3>
                <p><?php echo nl2br(htmlspecialchars($booking['remarks'])); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
$db->closeConnection();
include '../includes/footer.php'; 
?>
