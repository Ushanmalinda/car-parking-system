<?php
require_once '../includes/functions.php';
requireAdmin();

$db = new Database();
$conn = $db->getConnection();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == 'add_category') {
            $category_name = sanitize($_POST['category_name']);
            $category_type = sanitize($_POST['category_type']);
            $rate_per_hour = floatval($_POST['rate_per_hour']);
            $description = sanitize($_POST['description']);
            
            $stmt = $conn->prepare("INSERT INTO vehicle_categories (category_name, category_type, rate_per_hour, description) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $category_name, $category_type, $rate_per_hour, $description);
            
            if ($stmt->execute()) {
                setFlashMessage('success', 'Vehicle category added successfully');
            } else {
                setFlashMessage('error', 'Error adding category');
            }
            $stmt->close();
            header('Location: vehicle-categories.php');
            exit();
        }
        
        if ($action == 'edit_category') {
            $id = intval($_POST['id']);
            $category_name = sanitize($_POST['category_name']);
            $category_type = sanitize($_POST['category_type']);
            $rate_per_hour = floatval($_POST['rate_per_hour']);
            $description = sanitize($_POST['description']);
            
            $stmt = $conn->prepare("UPDATE vehicle_categories SET category_name = ?, category_type = ?, rate_per_hour = ?, description = ? WHERE id = ?");
            $stmt->bind_param("ssdsi", $category_name, $category_type, $rate_per_hour, $description, $id);
            
            if ($stmt->execute()) {
                setFlashMessage('success', 'Vehicle category updated successfully');
            } else {
                setFlashMessage('error', 'Error updating category');
            }
            $stmt->close();
            header('Location: vehicle-categories.php');
            exit();
        }
        
        if ($action == 'delete_category') {
            $id = intval($_POST['id']);
            $conn->query("DELETE FROM vehicle_categories WHERE id = $id");
            setFlashMessage('success', 'Vehicle category deleted successfully');
            header('Location: vehicle-categories.php');
            exit();
        }
    }
}

// Get all categories
$categories = [];
$result = $db->query("SELECT * FROM vehicle_categories ORDER BY category_type, category_name");
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

$page_title = 'Vehicle Categories';
include '../includes/header.php';
?>

<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="page-header">
            <h1>Vehicle Categories</h1>
            <button class="btn btn-primary" onclick="openModal('addModal')">Add Category</button>
        </div>
        
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Type</th>
                        <th>Rate per Hour</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="5" class="text-center">No categories found</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($category['category_name']); ?></td>
                        <td><?php echo ucwords(str_replace('_', ' ', $category['category_type'])); ?></td>
                        <td><?php echo formatCurrency($category['rate_per_hour'], $db); ?></td>
                        <td><?php echo htmlspecialchars($category['description']); ?></td>
                        <td>
                            <button onclick='editCategory(<?php echo json_encode($category); ?>)' class="btn-sm btn-info">Edit</button>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this category?');">
                                <input type="hidden" name="action" value="delete_category">
                                <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
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

<!-- Add Category Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add Vehicle Category</h2>
            <span class="close" onclick="closeModal('addModal')">&times;</span>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="add_category">
            <div class="form-group">
                <label>Category Name *</label>
                <input type="text" name="category_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Vehicle Type *</label>
                <select name="category_type" class="form-control" required>
                    <option value="two_wheeler">Two Wheeler</option>
                    <option value="four_wheeler">Four Wheeler</option>
                </select>
            </div>
            <div class="form-group">
                <label>Rate per Hour (₹) *</label>
                <input type="number" step="0.01" name="rate_per_hour" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Category</button>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Vehicle Category</h2>
            <span class="close" onclick="closeModal('editModal')">&times;</span>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="edit_category">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-group">
                <label>Category Name *</label>
                <input type="text" name="category_name" id="edit_category_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Vehicle Type *</label>
                <select name="category_type" id="edit_category_type" class="form-control" required>
                    <option value="two_wheeler">Two Wheeler</option>
                    <option value="four_wheeler">Four Wheeler</option>
                </select>
            </div>
            <div class="form-group">
                <label>Rate per Hour (₹) *</label>
                <input type="number" step="0.01" name="rate_per_hour" id="edit_rate_per_hour" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Category</button>
        </form>
    </div>
</div>

<script>
function editCategory(category) {
    document.getElementById('edit_id').value = category.id;
    document.getElementById('edit_category_name').value = category.category_name;
    document.getElementById('edit_category_type').value = category.category_type;
    document.getElementById('edit_rate_per_hour').value = category.rate_per_hour;
    document.getElementById('edit_description').value = category.description;
    openModal('editModal');
}
</script>

<?php 
$db->closeConnection();
include '../includes/footer.php'; 
?>
