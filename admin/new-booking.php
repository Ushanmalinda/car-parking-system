<?php
require_once '../includes/functions.php';
requireAdmin();

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $owner_name = sanitize($_POST['owner_name']);
    $owner_phone = sanitize($_POST['owner_phone']);
    $owner_email = sanitize($_POST['owner_email']);
    $vehicle_number = strtoupper(sanitize($_POST['vehicle_number']));
    $vehicle_type = sanitize($_POST['vehicle_type']);
    $vehicle_category_id = intval($_POST['vehicle_category_id']);
    $vehicle_company = sanitize($_POST['vehicle_company']);
    $slot_id = intval($_POST['slot_id']);
    $remarks = sanitize($_POST['remarks']);
    
    // Generate booking number
    $booking_number = generateBookingNumber();
    $entry_time = date('Y-m-d H:i:s');
    $created_by = $_SESSION['user_id'];
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Insert booking
        $stmt = $conn->prepare("INSERT INTO parking_bookings (booking_number, slot_id, owner_name, owner_phone, owner_email, vehicle_number, vehicle_type, vehicle_category_id, vehicle_company, entry_time, status, remarks, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'parked', ?, ?)");
        $stmt->bind_param("sisssssissii", $booking_number, $slot_id, $owner_name, $owner_phone, $owner_email, $vehicle_number, $vehicle_type, $vehicle_category_id, $vehicle_company, $entry_time, $remarks, $created_by);
        $stmt->execute();
        $stmt->close();
        
        // Update slot status
        $conn->query("UPDATE parking_slots SET status = 'occupied' WHERE id = $slot_id");
        
        // Log activity
        logActivity($db, $_SESSION['user_id'], 'New Booking', "Created booking: $booking_number");
        
        $conn->commit();
        setFlashMessage('success', "Booking created successfully! Booking Number: $booking_number");
        header('Location: bookings.php');
        exit();
        
    } catch (Exception $e) {
        $conn->rollback();
        setFlashMessage('error', 'Error creating booking: ' . $e->getMessage());
    }
}

// Get available slots
$available_slots = [];
$result = $db->query("SELECT * FROM parking_slots WHERE status = 'available' ORDER BY slot_number");
while ($row = $result->fetch_assoc()) {
    $available_slots[] = $row;
}

// Get vehicle categories
$categories = [];
$result = $db->query("SELECT * FROM vehicle_categories ORDER BY category_name");
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

$page_title = 'New Booking';
include '../includes/header.php';
?>

<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="page-header">
            <h1>New Parking Booking</h1>
            <a href="bookings.php" class="btn btn-secondary">View All Bookings</a>
        </div>
        
        <div class="form-container">
            <form method="POST" id="bookingForm">
                <div class="form-row">
                    <div class="form-group">
                        <label>Owner Name *</label>
                        <input type="text" name="owner_name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Owner Phone *</label>
                        <input type="tel" name="owner_phone" class="form-control" pattern="[0-9]{10}" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Owner Email</label>
                        <input type="email" name="owner_email" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Vehicle Number *</label>
                        <input type="text" name="vehicle_number" class="form-control" style="text-transform: uppercase;" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Vehicle Type *</label>
                        <select name="vehicle_type" id="vehicle_type" class="form-control" required onchange="filterSlots()">
                            <option value="">Select Type</option>
                            <option value="two_wheeler">Two Wheeler</option>
                            <option value="four_wheeler">Four Wheeler</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Vehicle Category *</label>
                        <select name="vehicle_category_id" id="vehicle_category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" data-type="<?php echo $category['category_type']; ?>">
                                <?php echo htmlspecialchars($category['category_name']); ?> (₹<?php echo $category['rate_per_hour']; ?>/hr)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Vehicle Company</label>
                        <input type="text" name="vehicle_company" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Parking Slot *</label>
                        <select name="slot_id" id="slot_id" class="form-control" required>
                            <option value="">Select Slot</option>
                            <?php foreach ($available_slots as $slot): ?>
                            <option value="<?php echo $slot['id']; ?>" data-type="<?php echo $slot['vehicle_type']; ?>">
                                <?php echo htmlspecialchars($slot['slot_number']); ?> - <?php echo htmlspecialchars($slot['slot_floor']); ?> Floor
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Remarks</label>
                    <textarea name="remarks" class="form-control" rows="3"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Create Booking</button>
            </form>
        </div>
    </div>
</div>

<script>
function filterSlots() {
    const vehicleType = document.getElementById('vehicle_type').value;
    const slotSelect = document.getElementById('slot_id');
    const categorySelect = document.getElementById('vehicle_category_id');
    const options = slotSelect.getElementsByTagName('option');
    const categoryOptions = categorySelect.getElementsByTagName('option');
    
    // Filter slots
    for (let i = 1; i < options.length; i++) {
        if (vehicleType === '' || options[i].getAttribute('data-type') === vehicleType) {
            options[i].style.display = '';
        } else {
            options[i].style.display = 'none';
        }
    }
    
    // Filter categories
    for (let i = 1; i < categoryOptions.length; i++) {
        if (vehicleType === '' || categoryOptions[i].getAttribute('data-type') === vehicleType) {
            categoryOptions[i].style.display = '';
        } else {
            categoryOptions[i].style.display = 'none';
        }
    }
    
    slotSelect.value = '';
    categorySelect.value = '';
}
</script>

<?php 
$db->closeConnection();
include '../includes/footer.php'; 
?>
