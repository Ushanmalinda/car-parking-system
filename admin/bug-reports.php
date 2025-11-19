<?php
require_once '../includes/functions.php';
requireAdmin();

$db = new Database();
$conn = $db->getConnection();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $report_id = intval($_POST['report_id']);
    $status = sanitize($_POST['status']);
    $admin_notes = sanitize($_POST['admin_notes']);
    
    $resolved_by = $_SESSION['user_id'];
    $resolved_at = ($status == 'resolved' || $status == 'closed') ? date('Y-m-d H:i:s') : null;
    
    $stmt = $conn->prepare("UPDATE bug_reports SET status = ?, admin_notes = ?, resolved_by = ?, resolved_at = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->bind_param("ssisi", $status, $admin_notes, $resolved_by, $resolved_at, $report_id);
    
    if ($stmt->execute()) {
        logActivity($db, $_SESSION['user_id'], 'Update Bug Report', "Bug report #$report_id status updated to $status");
        setFlashMessage('success', 'Bug report updated successfully');
    } else {
        setFlashMessage('error', 'Failed to update bug report');
    }
    $stmt->close();
    header('Location: bug-reports.php');
    exit();
}

// Handle delete
if (isset($_GET['delete']) && isSystemAdmin()) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM bug_reports WHERE id = $id");
    logActivity($db, $_SESSION['user_id'], 'Delete Bug Report', "Bug report #$id deleted");
    setFlashMessage('success', 'Bug report deleted');
    header('Location: bug-reports.php');
    exit();
}

// Get filter
$filter_status = isset($_GET['status']) ? sanitize($_GET['status']) : 'all';
$filter_priority = isset($_GET['priority']) ? sanitize($_GET['priority']) : 'all';

// Build query
$where = [];
if ($filter_status != 'all') {
    $where[] = "status = '" . $conn->real_escape_string($filter_status) . "'";
}
if ($filter_priority != 'all') {
    $where[] = "priority = '" . $conn->real_escape_string($filter_priority) . "'";
}
$where_clause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// Get bug reports
$reports = [];
$result = $conn->query("SELECT br.*, u.full_name as resolved_by_name 
                        FROM bug_reports br 
                        LEFT JOIN users u ON br.resolved_by = u.id 
                        $where_clause
                        ORDER BY br.created_at DESC");
while ($row = $result->fetch_assoc()) {
    $reports[] = $row;
}

// Get statistics
$stats = [];
$stats['total'] = $conn->query("SELECT COUNT(*) as count FROM bug_reports")->fetch_assoc()['count'];
$stats['new'] = $conn->query("SELECT COUNT(*) as count FROM bug_reports WHERE status = 'new'")->fetch_assoc()['count'];
$stats['in_progress'] = $conn->query("SELECT COUNT(*) as count FROM bug_reports WHERE status = 'in_progress'")->fetch_assoc()['count'];
$stats['resolved'] = $conn->query("SELECT COUNT(*) as count FROM bug_reports WHERE status = 'resolved'")->fetch_assoc()['count'];
$stats['critical'] = $conn->query("SELECT COUNT(*) as count FROM bug_reports WHERE priority = 'critical' AND status != 'resolved'")->fetch_assoc()['count'];

$page_title = 'Bug Reports';
include '../includes/header.php';
?>

<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="page-header">
            <h1>🐛 Bug Reports</h1>
            <div>
                <?php if (isSystemAdmin()): ?>
                <a href="support-settings.php" class="btn btn-secondary">⚙️ Support Settings</a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 30px;">
            <div class="stat-card">
                <div class="stat-icon blue"><span>📋</span></div>
                <div class="stat-details">
                    <h3><?php echo $stats['total']; ?></h3>
                    <p>Total Reports</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon orange"><span>🆕</span></div>
                <div class="stat-details">
                    <h3><?php echo $stats['new']; ?></h3>
                    <p>New</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon purple"><span>⏳</span></div>
                <div class="stat-details">
                    <h3><?php echo $stats['in_progress']; ?></h3>
                    <p>In Progress</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon green"><span>✅</span></div>
                <div class="stat-details">
                    <h3><?php echo $stats['resolved']; ?></h3>
                    <p>Resolved</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon red"><span>🚨</span></div>
                <div class="stat-details">
                    <h3><?php echo $stats['critical']; ?></h3>
                    <p>Critical</p>
                </div>
            </div>
        </div>
        
        <div class="filter-section" style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <form method="GET" style="display: flex; gap: 15px; align-items: end;">
                <div class="form-group" style="margin: 0;">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="all" <?php echo $filter_status == 'all' ? 'selected' : ''; ?>>All Status</option>
                        <option value="new" <?php echo $filter_status == 'new' ? 'selected' : ''; ?>>New</option>
                        <option value="in_progress" <?php echo $filter_status == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                        <option value="resolved" <?php echo $filter_status == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                        <option value="closed" <?php echo $filter_status == 'closed' ? 'selected' : ''; ?>>Closed</option>
                    </select>
                </div>
                
                <div class="form-group" style="margin: 0;">
                    <label>Priority</label>
                    <select name="priority" class="form-control">
                        <option value="all" <?php echo $filter_priority == 'all' ? 'selected' : ''; ?>>All Priorities</option>
                        <option value="critical" <?php echo $filter_priority == 'critical' ? 'selected' : ''; ?>>Critical</option>
                        <option value="high" <?php echo $filter_priority == 'high' ? 'selected' : ''; ?>>High</option>
                        <option value="medium" <?php echo $filter_priority == 'medium' ? 'selected' : ''; ?>>Medium</option>
                        <option value="low" <?php echo $filter_priority == 'low' ? 'selected' : ''; ?>>Low</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary" style="margin: 0;">🔍 Filter</button>
                <a href="bug-reports.php" class="btn btn-secondary" style="margin: 0;">Clear</a>
            </form>
        </div>
        
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Reporter</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reports)): ?>
                    <tr>
                        <td colspan="8" class="text-center">No bug reports found</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($reports as $report): ?>
                    <tr>
                        <td>#<?php echo $report['id']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($report['user_name']); ?></strong><br>
                            <small><?php echo htmlspecialchars($report['user_email']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($report['bug_title']); ?></td>
                        <td><span class="badge badge-<?php echo $report['bug_type']; ?>"><?php echo ucfirst(str_replace('_', ' ', $report['bug_type'])); ?></span></td>
                        <td><span class="badge badge-<?php echo $report['priority']; ?>"><?php echo ucfirst($report['priority']); ?></span></td>
                        <td><span class="badge badge-<?php echo $report['status']; ?>"><?php echo ucfirst(str_replace('_', ' ', $report['status'])); ?></span></td>
                        <td><?php echo formatDate($report['created_at']); ?></td>
                        <td>
                            <button onclick='viewReport(<?php echo json_encode($report); ?>)' class="btn-sm btn-info">View</button>
                            <?php if (isSystemAdmin()): ?>
                            <a href="?delete=<?php echo $report['id']; ?>" class="btn-sm btn-danger" 
                               onclick="return confirm('Delete this bug report?')">Delete</a>
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

<!-- View Report Modal -->
<div id="viewModal" class="modal">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h2>Bug Report Details</h2>
            <span class="close" onclick="closeModal('viewModal')">&times;</span>
        </div>
        <div class="modal-body" id="reportDetails"></div>
        <div class="modal-footer">
            <button onclick="closeModal('viewModal')" class="btn btn-secondary">Close</button>
        </div>
    </div>
</div>

<script>
function viewReport(report) {
    const html = `
        <div style="padding: 20px;">
            <h3>${report.bug_title}</h3>
            
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin: 20px 0;">
                <div><strong>Reporter:</strong> ${report.user_name}</div>
                <div><strong>Email:</strong> ${report.user_email}</div>
                <div><strong>Phone:</strong> ${report.user_phone || 'N/A'}</div>
                <div><strong>Type:</strong> <span class="badge badge-${report.bug_type}">${report.bug_type.replace('_', ' ')}</span></div>
                <div><strong>Priority:</strong> <span class="badge badge-${report.priority}">${report.priority}</span></div>
                <div><strong>Status:</strong> <span class="badge badge-${report.status}">${report.status.replace('_', ' ')}</span></div>
                <div><strong>Date:</strong> ${report.created_at}</div>
                <div><strong>Page:</strong> ${report.page_url || 'N/A'}</div>
            </div>
            
            <div style="margin: 20px 0;">
                <strong>Description:</strong>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 10px; white-space: pre-wrap;">
${report.bug_description}
                </div>
            </div>
            
            ${report.browser_info ? `
            <div style="margin: 20px 0;">
                <strong>Browser Info:</strong>
                <div style="background: #f8f9fa; padding: 10px; border-radius: 8px; margin-top: 10px; font-size: 12px;">
${report.browser_info}
                </div>
            </div>
            ` : ''}
            
            ${report.admin_notes ? `
            <div style="margin: 20px 0;">
                <strong>Admin Notes:</strong>
                <div style="background: #fff3cd; padding: 15px; border-radius: 8px; margin-top: 10px;">
${report.admin_notes}
                </div>
            </div>
            ` : ''}
            
            ${report.resolved_by_name ? `
            <div style="margin: 20px 0;">
                <strong>Resolved By:</strong> ${report.resolved_by_name} on ${report.resolved_at}
            </div>
            ` : ''}
            
            <form method="POST" style="margin-top: 30px; border-top: 2px solid #ecf0f1; padding-top: 20px;">
                <input type="hidden" name="update_status" value="1">
                <input type="hidden" name="report_id" value="${report.id}">
                
                <div class="form-group">
                    <label>Update Status</label>
                    <select name="status" class="form-control" required>
                        <option value="new" ${report.status == 'new' ? 'selected' : ''}>New</option>
                        <option value="in_progress" ${report.status == 'in_progress' ? 'selected' : ''}>In Progress</option>
                        <option value="resolved" ${report.status == 'resolved' ? 'selected' : ''}>Resolved</option>
                        <option value="closed" ${report.status == 'closed' ? 'selected' : ''}>Closed</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Admin Notes</label>
                    <textarea name="admin_notes" class="form-control" rows="4">${report.admin_notes || ''}</textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Update Report</button>
            </form>
        </div>
    `;
    
    document.getElementById('reportDetails').innerHTML = html;
    openModal('viewModal');
}
</script>

<style>
.badge-bug { background: #e74c3c; color: white; }
.badge-error { background: #c0392b; color: white; }
.badge-feature_request { background: #3498db; color: white; }
.badge-question { background: #9b59b6; color: white; }
.badge-other { background: #95a5a6; color: white; }

.badge-low { background: #95a5a6; color: white; }
.badge-medium { background: #f39c12; color: white; }
.badge-high { background: #e67e22; color: white; }
.badge-critical { background: #e74c3c; color: white; }

.badge-new { background: #3498db; color: white; }
.badge-in_progress { background: #f39c12; color: white; }
.badge-resolved { background: #27ae60; color: white; }
.badge-closed { background: #95a5a6; color: white; }
</style>

<?php 
$db->closeConnection();
include '../includes/footer.php'; 
?>
