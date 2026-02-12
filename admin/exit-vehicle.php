<?php
require_once '../includes/functions.php';
requireAdmin();

$db = new Database();
$conn = $db->getConnection();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $exit_time = date('Y-m-d H:i:s');
    $payment_method = sanitize($_POST['payment_method']);
    
    // Get booking details
    $stmt = $conn->prepare("SELECT * FROM parking_bookings WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $booking = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if ($booking && $booking['status'] == 'parked') {
        // Calculate parking charge
        $parking_charge = calculateParkingCharge($booking['entry_time'], $exit_time, $booking['vehicle_type']);
        
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // Update booking
            $stmt = $conn->prepare("UPDATE parking_bookings SET exit_time = ?, parking_charge = ?, status = 'exited' WHERE id = ?");
            $stmt->bind_param("sdi", $exit_time, $parking_charge, $id);
            $stmt->execute();
            $stmt->close();
            
            // Update slot status
            $conn->query("UPDATE parking_slots SET status = 'available' WHERE id = " . $booking['slot_id']);
            
            // Add payment record
            $stmt = $conn->prepare("INSERT INTO payments (booking_id, payment_amount, payment_method, payment_status, payment_date) VALUES (?, ?, ?, 'paid', ?)");
            $stmt->bind_param("idss", $id, $parking_charge, $payment_method, $exit_time);
            $stmt->execute();
            $stmt->close();
            
            // Log activity
            logActivity($db, $_SESSION['user_id'], 'Exit Vehicle', "Vehicle exited: " . $booking['booking_number']);
            
            $conn->commit();
            setFlashMessage('success', 'Vehicle exited successfully! Parking charge: ₹' . number_format($parking_charge, 2));
            header('Location: view-booking.php?id=' . $id);
            exit();
            
        } catch (Exception $e) {
            $conn->rollback();
            setFlashMessage('error', 'Error processing exit: ' . $e->getMessage());
        }
    } else {
        setFlashMessage('error', 'Invalid booking or vehicle already exited');
        header('Location: bookings.php');
        exit();
    }
}

// Get booking details
$stmt = $conn->prepare("SELECT pb.*, ps.slot_number, ps.slot_floor FROM parking_bookings pb 
                        JOIN parking_slots ps ON pb.slot_id = ps.id WHERE pb.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$booking || $booking['status'] != 'parked') {
    setFlashMessage('error', 'Booking not found or vehicle already exited');
    header('Location: bookings.php');
    exit();
}

// Calculate estimated charge
$estimated_charge = calculateParkingCharge($booking['entry_time'], date('Y-m-d H:i:s'), $booking['vehicle_type']);

$page_title = 'Exit Vehicle';
include '../includes/header.php';
?>

<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="page-header">
            <h1>Exit Vehicle</h1>
            <a href="bookings.php" class="btn btn-secondary">Back to Bookings</a>
        </div>
        
        <div class="form-container">
            <div class="booking-summary">
                <h3>Booking Details</h3>
                <div class="detail-row">
                    <span class="label">Booking Number:</span>
                    <span class="value"><?php echo htmlspecialchars($booking['booking_number']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Vehicle Number:</span>
                    <span class="value"><?php echo htmlspecialchars($booking['vehicle_number']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Owner Name:</span>
                    <span class="value"><?php echo htmlspecialchars($booking['owner_name']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Parking Slot:</span>
                    <span class="value"><?php echo htmlspecialchars($booking['slot_number']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Entry Time:</span>
                    <span class="value"><?php echo formatDate($booking['entry_time']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Current Time:</span>
                    <span class="value"><?php echo date('d-m-Y H:i:s'); ?></span>
                </div>
                <div class="detail-row highlight">
                    <span class="label">Estimated Charge:</span>
                    <span class="value">₹<?php echo number_format($estimated_charge, 2); ?></span>
                </div>
            </div>
            
            <form method="POST" class="exit-form">
                <div class="form-group">
                    <label>Payment Method *</label>
                    <select name="payment_method" class="form-control" required>
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="upi">UPI</option>
                        <option value="online">Online</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Confirm vehicle exit?')">
                    Process Exit & Generate Bill
                </button>
            </form>
        </div>
    </div>
</div>

<?php 
$db->closeConnection();
include '../includes/footer.php'; 
?>
