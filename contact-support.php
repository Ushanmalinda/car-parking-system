<?php
require_once 'includes/functions.php';

$db = new Database();
$conn = $db->getConnection();

// Get support contact
$result = $conn->query("SELECT * FROM support_contacts WHERE is_active = 1 LIMIT 1");
$contact = $result->fetch_assoc();

if (!$contact) {
    setFlashMessage('error', 'Support contact is not configured');
    header('Location: index.php');
    exit();
}

// Handle bug report submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_bug'])) {
    $user_name = sanitize($_POST['user_name']);
    $user_email = sanitize($_POST['user_email']);
    $user_phone = sanitize($_POST['user_phone']);
    $bug_title = sanitize($_POST['bug_title']);
    $bug_description = sanitize($_POST['bug_description']);
    $bug_type = sanitize($_POST['bug_type']);
    $priority = sanitize($_POST['priority']);
    $page_url = sanitize($_POST['page_url']);
    $browser_info = $_SERVER['HTTP_USER_AGENT'];
    
    $user_id = isLoggedIn() ? $_SESSION['user_id'] : null;
    
    $stmt = $conn->prepare("INSERT INTO bug_reports (user_id, user_name, user_email, user_phone, bug_title, bug_description, bug_type, priority, page_url, browser_info) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssss", $user_id, $user_name, $user_email, $user_phone, $bug_title, $bug_description, $bug_type, $priority, $page_url, $browser_info);
    
    if ($stmt->execute()) {
        setFlashMessage('success', 'Bug report submitted successfully! We will contact you soon.');
        header('Location: contact-support.php?success=1');
        exit();
    } else {
        setFlashMessage('error', 'Failed to submit bug report. Please try again.');
    }
    $stmt->close();
}

$page_title = 'Contact Support';
include 'includes/header.php';
?>

<style>
.support-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 20px;
}
.support-header {
    text-align: center;
    margin-bottom: 40px;
}
.support-header h1 {
    color: #2c3e50;
    font-size: 36px;
    margin-bottom: 10px;
}
.support-header p {
    color: #7f8c8d;
    font-size: 18px;
}
.contact-methods {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}
.contact-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}
.contact-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 12px rgba(0,0,0,0.15);
}
.contact-card .icon {
    font-size: 48px;
    margin-bottom: 15px;
}
.contact-card h3 {
    color: #2c3e50;
    margin-bottom: 10px;
    font-size: 22px;
}
.contact-card p {
    color: #7f8c8d;
    margin-bottom: 20px;
    font-size: 14px;
}
.contact-card .btn {
    width: 100%;
    padding: 12px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 8px;
    text-decoration: none;
    display: inline-block;
}
.btn-email {
    background: #e74c3c;
    color: white;
}
.btn-email:hover {
    background: #c0392b;
}
.btn-whatsapp {
    background: #25D366;
    color: white;
}
.btn-whatsapp:hover {
    background: #1da851;
}
.btn-phone {
    background: #3498db;
    color: white;
}
.btn-phone:hover {
    background: #2980b9;
}
.bug-report-section {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin-top: 40px;
}
.bug-report-section h2 {
    color: #2c3e50;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.form-container {
    max-width: 100%;
}
.success-message {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: center;
}
</style>

<div class="support-container">
    <div class="support-header">
        <h1>🛠️ Contact Support</h1>
        <p>Found a bug or need help? We're here to assist you!</p>
    </div>

    <?php if (isset($_GET['success'])): ?>
    <div class="success-message">
        <h3>✅ Thank You!</h3>
        <p>Your bug report has been submitted successfully. We will review it and contact you soon.</p>
    </div>
    <?php endif; ?>

    <div class="contact-methods">
        <div class="contact-card">
            <div class="icon">📧</div>
            <h3>Email Support</h3>
            <p><?php echo htmlspecialchars($contact['support_email']); ?></p>
            <a href="mailto:<?php echo htmlspecialchars($contact['support_email']); ?>?subject=Bug Report - Parking System&body=Hi <?php echo htmlspecialchars($contact['support_name']); ?>,%0D%0A%0D%0AI found an issue with the parking system:%0D%0A%0D%0ABug Title: [Describe the issue briefly]%0D%0A%0D%0ADescription:%0D%0A[Provide detailed description]%0D%0A%0D%0APage/Section: [Where did you find the bug?]%0D%0A%0D%0ASteps to Reproduce:%0D%0A1. %0D%0A2. %0D%0A3. %0D%0A%0D%0AExpected Result: [What should happen?]%0D%0AActual Result: [What actually happened?]%0D%0A%0D%0AThanks!" 
               class="btn btn-email" target="_blank">
                Send Email
            </a>
        </div>

        <div class="contact-card">
            <div class="icon">💬</div>
            <h3>WhatsApp</h3>
            <p><?php echo htmlspecialchars($contact['whatsapp_number']); ?></p>
            <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $contact['whatsapp_number']); ?>?text=Hi%20<?php echo urlencode($contact['support_name']); ?>,%20I%20found%20a%20bug%20in%20the%20parking%20system.%20Can%20you%20help?" 
               class="btn btn-whatsapp" target="_blank">
                Chat on WhatsApp
            </a>
        </div>

        <div class="contact-card">
            <div class="icon">📱</div>
            <h3>Phone Support</h3>
            <p><?php echo htmlspecialchars($contact['support_phone']); ?></p>
            <a href="tel:<?php echo htmlspecialchars($contact['support_phone']); ?>" 
               class="btn btn-phone">
                Call Now
            </a>
        </div>
    </div>

    <div class="bug-report-section">
        <h2>🐛 Report a Bug</h2>
        <p style="color: #7f8c8d; margin-bottom: 20px;">Fill out the form below to report a bug or issue. We'll get back to you as soon as possible.</p>
        
        <form method="POST" class="form-container">
            <input type="hidden" name="submit_bug" value="1">
            
            <h3>Your Information</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Your Name *</label>
                    <input type="text" name="user_name" class="form-control" 
                           value="<?php echo isLoggedIn() ? htmlspecialchars($_SESSION['full_name']) : ''; ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label>Your Email *</label>
                    <input type="email" name="user_email" class="form-control" 
                           required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Your Phone</label>
                <input type="tel" name="user_phone" class="form-control" 
                       placeholder="+1234567890">
            </div>
            
            <h3 style="margin-top: 30px;">Bug Details</h3>
            
            <div class="form-group">
                <label>Bug Title *</label>
                <input type="text" name="bug_title" class="form-control" 
                       placeholder="Brief description of the issue" required>
            </div>
            
            <div class="form-group">
                <label>Detailed Description *</label>
                <textarea name="bug_description" class="form-control" rows="6" 
                          placeholder="Please describe the bug in detail:&#10;- What happened?&#10;- What were you trying to do?&#10;- What did you expect to happen?&#10;- Steps to reproduce the issue" 
                          required></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Bug Type *</label>
                    <select name="bug_type" class="form-control" required>
                        <option value="bug">🐛 Bug/Error</option>
                        <option value="error">❌ Critical Error</option>
                        <option value="feature_request">💡 Feature Request</option>
                        <option value="question">❓ Question</option>
                        <option value="other">📋 Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Priority</label>
                    <select name="priority" class="form-control">
                        <option value="low">Low - Minor issue</option>
                        <option value="medium" selected>Medium - Affects workflow</option>
                        <option value="high">High - Major issue</option>
                        <option value="critical">Critical - System down</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Page/URL where you found the bug</label>
                <input type="url" name="page_url" class="form-control" 
                       placeholder="https://example.com/page" 
                       value="<?php echo isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : ''; ?>">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: auto; padding: 15px 40px;">
                📤 Submit Bug Report
            </button>
        </form>
    </div>

    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 30px; text-align: center;">
        <h3 style="color: #2c3e50; margin-bottom: 10px;">Need Immediate Help?</h3>
        <p style="color: #7f8c8d;">Contact <strong><?php echo htmlspecialchars($contact['support_name']); ?></strong></p>
        <?php if (!empty($contact['company_name'])): ?>
        <p style="color: #95a5a6; font-size: 14px;"><?php echo htmlspecialchars($contact['company_name']); ?></p>
        <?php endif; ?>
    </div>
</div>

<?php 
$db->closeConnection();
include 'includes/footer.php'; 
?>
