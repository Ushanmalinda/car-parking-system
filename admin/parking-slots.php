<?php
require_once '../includes/functions.php';
requireAdmin();

$db = new Database();
$conn = $db->getConnection();
$message = '';

// Handle Add/Edit/Delete operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == 'add') {
            $slot_number = sanitize($_POST['slot_number']);
            $slot_floor = sanitize($_POST['slot_floor']);
            $vehicle_type = sanitize($_POST['vehicle_type']);
            $status = sanitize($_POST['status']);
            
            $stmt = $conn->prepare("INSERT INTO parking_slots (slot_number, slot_floor, vehicle_type, status) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $slot_number, $slot_floor, $vehicle_type, $status);
            
            if ($stmt->execute()) {
                setFlashMessage('success', 'Parking slot added successfully');
                logActivity($db, $_SESSION['user_id'], 'Add Slot', "Added parking slot: $slot_number");
            } else {
                setFlashMessage('error', 'Error adding parking slot');
            }
            $stmt->close();
            header('Location: parking-slots.php');
            exit();
        }
        
        if ($action == 'edit') {
            $id = intval($_POST['id']);
            $slot_number = sanitize($_POST['slot_number']);
            $slot_floor = sanitize($_POST['slot_floor']);
            $vehicle_type = sanitize($_POST['vehicle_type']);
            $status = sanitize($_POST['status']);
            
            $stmt = $conn->prepare("UPDATE parking_slots SET slot_number = ?, slot_floor = ?, vehicle_type = ?, status = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $slot_number, $slot_floor, $vehicle_type, $status, $id);
            
            if ($stmt->execute()) {
                setFlashMessage('success', 'Parking slot updated successfully');
                logActivity($db, $_SESSION['user_id'], 'Update Slot', "Updated parking slot: $slot_number");
            } else {
                setFlashMessage('error', 'Error updating parking slot');
            }
            $stmt->close();
            header('Location: parking-slots.php');
            exit();
        }
        
        if ($action == 'delete') {
            $id = intval($_POST['id']);
            
            // Check if slot has active bookings
            $check = $conn->query("SELECT COUNT(*) as count FROM parking_bookings WHERE slot_id = $id AND status = 'parked'");
            $result = $check->fetch_assoc();
            
            if ($result['count'] > 0) {
                setFlashMessage('error', 'Cannot delete slot with active bookings');
            } else {
                $conn->query("DELETE FROM parking_slots WHERE id = $id");
                setFlashMessage('success', 'Parking slot deleted successfully');
                logActivity($db, $_SESSION['user_id'], 'Delete Slot', "Deleted parking slot ID: $id");
            }
            header('Location: parking-slots.php');
            exit();
        }
    }
}

// Get all parking slots
$slots = [];
$result = $db->query("SELECT * FROM parking_slots ORDER BY slot_number");
while ($row = $result->fetch_assoc()) {
    $slots[] = $row;
}

$page_title = 'Parking Slots';
include '../includes/header.php';
?>

<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="page-header">
            <h1>Parking Slots Management</h1>
            <button class="btn btn-primary" onclick="openModal('addModal')">Add New Slot</button>
        </div>
        
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Slot Number</th>
                        <th>Floor</th>
                        <th>Vehicle Type</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($slots)): ?>
                    <tr>
                        <td colspan="6" class="text-center">No parking slots found</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($slots as $slot): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($slot['slot_number']); ?></td>
                        <td><?php echo htmlspecialchars($slot['slot_floor']); ?></td>
                        <td><?php echo ucwords(str_replace('_', ' ', $slot['vehicle_type'])); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $slot['status']; ?>">
                                <?php echo ucfirst($slot['status']); ?>
                            </span>
                        </td>
                        <td><?php echo formatDate($slot['created_at']); ?></td>
                        <td>
                            <button onclick='editSlot(<?php echo json_encode($slot); ?>)' class="btn-sm btn-info">Edit</button>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this slot?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $slot['id']; ?>">
                                <button type="submit" class="btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Slot Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Parking Slot</h2>
            <span class="close" onclick="closeModal('addModal')">&times;</span>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label>Slot Number</label>
                <input type="text" name="slot_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Floor</label>
                <input type="text" name="slot_floor" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Vehicle Type</label>
                <select name="vehicle_type" class="form-control" required>
                    <option value="two_wheeler">Two Wheeler</option>
                    <option value="four_wheeler">Four Wheeler</option>
                </select>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="available">Available</option>
                    <option value="occupied">Occupied</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Slot</button>
        </form>
    </div>
</div>

<!-- Edit Slot Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Parking Slot</h2>
            <span class="close" onclick="closeModal('editModal')">&times;</span>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-group">
                <label>Slot Number</label>
                <input type="text" name="slot_number" id="edit_slot_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Floor</label>
                <input type="text" name="slot_floor" id="edit_slot_floor" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Vehicle Type</label>
                <select name="vehicle_type" id="edit_vehicle_type" class="form-control" required>
                    <option value="two_wheeler">Two Wheeler</option>
                    <option value="four_wheeler">Four Wheeler</option>
                </select>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" id="edit_status" class="form-control" required>
                    <option value="available">Available</option>
                    <option value="occupied">Occupied</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Slot</button>
        </form>
    </div>
</div>

<script>
function editSlot(slot) {
    document.getElementById('edit_id').value = slot.id;
    document.getElementById('edit_slot_number').value = slot.slot_number;
    document.getElementById('edit_slot_floor').value = slot.slot_floor;
    document.getElementById('edit_vehicle_type').value = slot.vehicle_type;
    document.getElementById('edit_status').value = slot.status;
    openModal('editModal');
}
</script>

<?php 
$db->closeConnection();
include '../includes/footer.php'; 
?>
