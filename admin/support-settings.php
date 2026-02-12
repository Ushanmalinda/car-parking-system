<?php
require_once '../includes/functions.php';

// Require system admin access - check if user is system admin
requireLogin();
if (!isSystemAdmin()) {
    setFlashMessage('error', 'Access denied. System Admin only.');
    header('Location: ' . SITE_URL . 'admin/dashboard.php');
    exit();
}

$db = new Database();
$conn = $db->getConnection();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $support_email = sanitize($_POST['support_email']);
    $support_phone = sanitize($_POST['support_phone']);
    $whatsapp_number = sanitize($_POST['whatsapp_number']);
    $support_name = sanitize($_POST['support_name']);
    $company_name = sanitize($_POST['company_name']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Validate email
    if (!filter_var($support_email, FILTER_VALIDATE_EMAIL)) {
        setFlashMessage('error', 'Invalid email address');
        header('Location: support-settings.php');
        exit();
    }
    
    // Check if contact exists
    $check = $conn->query("SELECT COUNT(*) as count FROM support_contacts");
    $row = $check->fetch_assoc();
    
    if ($row['count'] > 0) {
        // Update existing
        $stmt = $conn->prepare("UPDATE support_contacts SET support_email = ?, support_phone = ?, whatsapp_number = ?, support_name = ?, company_name = ?, is_active = ?, updated_by = ? WHERE id = 1");
        $stmt->bind_param("sssssii", $support_email, $support_phone, $whatsapp_number, $support_name, $company_name, $is_active, $_SESSION['user_id']);
    } else {
        // Insert new
        $stmt = $conn->prepare("INSERT INTO support_contacts (support_email, support_phone, whatsapp_number, support_name, company_name, is_active, updated_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssii", $support_email, $support_phone, $whatsapp_number, $support_name, $company_name, $is_active, $_SESSION['user_id']);
    }
    
    if ($stmt->execute()) {
        logActivity($db, $_SESSION['user_id'], 'Update Support Settings', 'Support contact information updated');
        setFlashMessage('success', 'Support settings updated successfully');
    } else {
        setFlashMessage('error', 'Failed to update support settings');
    }
    
    $stmt->close();
    header('Location: support-settings.php');
    exit();
}

// Get current support contact
$result = $conn->query("SELECT * FROM support_contacts WHERE id = 1");
$contact = $result->fetch_assoc();

if (!$contact) {
    // Default values
    $contact = [
        'support_email' => 'support@parkingsystem.com',
        'support_phone' => '+1234567890',
        'whatsapp_number' => '+1234567890',
        'support_name' => 'System Administrator',
        'company_name' => 'Parking Management System',
        'is_active' => 1
    ];
}

$page_title = 'Support Settings';
include '../includes/header.php';
?>

<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="page-header">
            <h1>🛠️ Support Contact Settings</h1>
            <span class="badge badge-system_admin">System Admin Only</span>
        </div>
        
        <div class="info-box" style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin-bottom: 20px;">
            <strong>ℹ️ Information:</strong><br>
            Configure your support contact details. Customers can use these to report bugs or contact you.
            These details will appear in the "Contact Support" page.
        </div>
        
        <div class="form-container">
            <form method="POST">
                <h3>Contact Information</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Support Name *</label>
                        <input type="text" name="support_name" class="form-control" 
                               value="<?php echo htmlspecialchars($contact['support_name']); ?>" 
                               placeholder="Your Name or Company Support Team" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Company Name</label>
                        <input type="text" name="company_name" class="form-control" 
                               value="<?php echo htmlspecialchars($contact['company_name'] ?? ''); ?>" 
                               placeholder="Your Company Name">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Support Email *</label>
                        <input type="email" name="support_email" class="form-control" 
                               value="<?php echo htmlspecialchars($contact['support_email']); ?>" 
                               placeholder="support@yourcompany.com" required>
                        <small>Customers will send bug reports to this email</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Support Phone *</label>
                        <input type="tel" name="support_phone" class="form-control" 
                               value="<?php echo htmlspecialchars($contact['support_phone']); ?>" 
                               placeholder="+1234567890" required>
                        <small>Include country code (e.g., +1, +91, +971)</small>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>WhatsApp Number *</label>
                    <input type="tel" name="whatsapp_number" class="form-control" 
                           value="<?php echo htmlspecialchars($contact['whatsapp_number']); ?>" 
                           placeholder="+1234567890" required>
                    <small>Include country code. Customers can contact you via WhatsApp.</small>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1" 
                               <?php echo $contact['is_active'] ? 'checked' : ''; ?>>
                        Enable Support Contact (Allow customers to contact you)
                    </label>
                </div>
                
                <div style="background: #e8f5e9; padding: 15px; border-radius: 8px; margin: 20px 0;">
                    <h4 style="margin-top: 0; color: #2e7d32;">Preview:</h4>
                    <p><strong>Name:</strong> <span id="preview_name"><?php echo htmlspecialchars($contact['support_name']); ?></span></p>
                    <p><strong>Email:</strong> <span id="preview_email"><?php echo htmlspecialchars($contact['support_email']); ?></span></p>
                    <p><strong>Phone:</strong> <span id="preview_phone"><?php echo htmlspecialchars($contact['support_phone']); ?></span></p>
                    <p><strong>WhatsApp:</strong> <span id="preview_whatsapp"><?php echo htmlspecialchars($contact['whatsapp_number']); ?></span></p>
                </div>
                
                <button type="submit" class="btn btn-primary">💾 Save Support Settings</button>
                <a href="bug-reports.php" class="btn btn-secondary">📋 View Bug Reports</a>
            </form>
        </div>
    </div>
</div>

<script>
// Live preview update
document.querySelectorAll('input[name]').forEach(input => {
    input.addEventListener('input', function() {
        const name = this.name;
        const value = this.value;
        const previewId = 'preview_' + name.replace('support_', '').replace('whatsapp_number', 'whatsapp');
        const previewElement = document.getElementById(previewId);
        if (previewElement) {
            previewElement.textContent = value || '[Not Set]';
        }
    });
});
</script>

<style>
.badge-system_admin {
    background: #9c27b0;
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 14px;
}
.checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: normal;
}
.checkbox-label input[type="checkbox"] {
    width: auto;
    margin: 0;
}
</style>

<?php 
$db->closeConnection();
include '../includes/footer.php'; 
?>
