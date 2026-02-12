<?php
require_once '../includes/functions.php';
require_once '../includes/currencies.php';
requireAdmin();

$db = new Database();
$conn = $db->getConnection();

// Handle auto-detect currency
if (isset($_GET['action']) && $_GET['action'] === 'detect_currency') {
    $detected = detectCurrencyFromIP();
    $currencyInfo = getCurrencyByCode($detected);
    echo json_encode([
        'success' => true,
        'currency' => $detected,
        'name' => $currencyInfo['name'],
        'symbol' => $currencyInfo['symbol']
    ]);
    exit();
}

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $two_wheeler_rate = floatval($_POST['two_wheeler_rate']);
    $four_wheeler_rate = floatval($_POST['four_wheeler_rate']);
    $site_name = sanitize($_POST['site_name']);
    $currency_code = sanitize($_POST['currency_code']);
    $timezone = sanitize($_POST['timezone']);
    
    // Update settings
    $conn->query("UPDATE system_settings SET setting_value = '$two_wheeler_rate' WHERE setting_key = 'two_wheeler_rate'");
    $conn->query("UPDATE system_settings SET setting_value = '$four_wheeler_rate' WHERE setting_key = 'four_wheeler_rate'");
    $conn->query("UPDATE system_settings SET setting_value = '$site_name' WHERE setting_key = 'site_name'");
    $conn->query("UPDATE system_settings SET setting_value = '$currency_code' WHERE setting_key = 'currency'");
    $conn->query("UPDATE system_settings SET setting_value = '$timezone' WHERE setting_key = 'timezone'");
    
    // Check if settings exist, if not insert them
    $check = $conn->query("SELECT COUNT(*) as count FROM system_settings WHERE setting_key = 'currency'");
    $row = $check->fetch_assoc();
    if ($row['count'] == 0) {
        $conn->query("INSERT INTO system_settings (setting_key, setting_value, description) VALUES ('currency', '$currency_code', 'System currency code')");
    }
    
    logActivity($db, $_SESSION['user_id'], 'Update Settings', 'System settings updated');
    setFlashMessage('success', 'Settings updated successfully');
    header('Location: settings.php');
    exit();
}

// Get current settings
$settings = [];
$result = $db->query("SELECT * FROM system_settings");
while ($row = $result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Default values if not set
$current_currency = $settings['currency'] ?? 'USD';
$current_timezone = $settings['timezone'] ?? 'Asia/Kolkata';

$page_title = 'Settings';
include '../includes/header.php';
?>

<div class="dashboard-wrapper">
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="page-header">
            <h1>System Settings</h1>
        </div>
        
        <div class="form-container">
            <form method="POST" id="settingsForm">
                <h3>General Settings</h3>
                
                <div class="form-group">
                    <label>Site Name</label>
                    <input type="text" name="site_name" class="form-control" 
                           value="<?php echo htmlspecialchars($settings['site_name'] ?? SITE_NAME); ?>" required>
                </div>
                
                <h3 style="margin-top: 30px;">Currency Settings</h3>
                
                <div class="form-row">
                    <div class="form-group" style="flex: 2;">
                        <label>Currency</label>
                        <select name="currency_code" id="currencySelect" class="form-control" required onchange="updateCurrencyPreview()">
                            <optgroup label="Popular Currencies">
                                <option value="USD" <?php echo $current_currency === 'USD' ? 'selected' : ''; ?>>USD - US Dollar ($)</option>
                                <option value="EUR" <?php echo $current_currency === 'EUR' ? 'selected' : ''; ?>>EUR - Euro (€)</option>
                                <option value="GBP" <?php echo $current_currency === 'GBP' ? 'selected' : ''; ?>>GBP - British Pound (£)</option>
                                <option value="INR" <?php echo $current_currency === 'INR' ? 'selected' : ''; ?>>INR - Indian Rupee (₹)</option>
                                <option value="JPY" <?php echo $current_currency === 'JPY' ? 'selected' : ''; ?>>JPY - Japanese Yen (¥)</option>
                                <option value="CNY" <?php echo $current_currency === 'CNY' ? 'selected' : ''; ?>>CNY - Chinese Yuan (¥)</option>
                            </optgroup>
                            <?php
                            $regions = getAllRegions();
                            foreach ($regions as $region) {
                                echo "<optgroup label='$region'>";
                                $currencies = getCurrenciesByRegion($region);
                                foreach ($currencies as $code => $currency) {
                                    $selected = $current_currency === $code ? 'selected' : '';
                                    echo "<option value='$code' $selected>$code - {$currency['name']} ({$currency['symbol']})</option>";
                                }
                                echo "</optgroup>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group" style="flex: 1;">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-secondary" onclick="autoDetectCurrency()" style="width: 100%;">
                            🌍 Auto-Detect
                        </button>
                    </div>
                </div>
                
                <div class="info-box" style="background: #e8f5e9; border-left: 4px solid #4caf50; padding: 15px; margin: 15px 0;">
                    <strong>💡 Currency Preview:</strong><br>
                    <span id="currencyPreview">Selected: <?php 
                        $curr = getCurrencyByCode($current_currency);
                        echo "{$curr['name']} ({$curr['symbol']}) - {$curr['region']}";
                    ?></span>
                </div>
                
                <h3 style="margin-top: 30px;">Regional Settings</h3>
                
                <div class="form-group">
                    <label>Timezone</label>
                    <select name="timezone" class="form-control" required>
                        <optgroup label="Asia">
                            <option value="Asia/Kolkata" <?php echo $current_timezone === 'Asia/Kolkata' ? 'selected' : ''; ?>>India (Kolkata)</option>
                            <option value="Asia/Dubai" <?php echo $current_timezone === 'Asia/Dubai' ? 'selected' : ''; ?>>UAE (Dubai)</option>
                            <option value="Asia/Singapore" <?php echo $current_timezone === 'Asia/Singapore' ? 'selected' : ''; ?>>Singapore</option>
                            <option value="Asia/Tokyo" <?php echo $current_timezone === 'Asia/Tokyo' ? 'selected' : ''; ?>>Japan (Tokyo)</option>
                            <option value="Asia/Shanghai" <?php echo $current_timezone === 'Asia/Shanghai' ? 'selected' : ''; ?>>China (Shanghai)</option>
                            <option value="Asia/Hong_Kong" <?php echo $current_timezone === 'Asia/Hong_Kong' ? 'selected' : ''; ?>>Hong Kong</option>
                            <option value="Asia/Seoul" <?php echo $current_timezone === 'Asia/Seoul' ? 'selected' : ''; ?>>South Korea (Seoul)</option>
                            <option value="Asia/Bangkok" <?php echo $current_timezone === 'Asia/Bangkok' ? 'selected' : ''; ?>>Thailand (Bangkok)</option>
                            <option value="Asia/Jakarta" <?php echo $current_timezone === 'Asia/Jakarta' ? 'selected' : ''; ?>>Indonesia (Jakarta)</option>
                        </optgroup>
                        <optgroup label="Europe">
                            <option value="Europe/London" <?php echo $current_timezone === 'Europe/London' ? 'selected' : ''; ?>>UK (London)</option>
                            <option value="Europe/Paris" <?php echo $current_timezone === 'Europe/Paris' ? 'selected' : ''; ?>>France (Paris)</option>
                            <option value="Europe/Berlin" <?php echo $current_timezone === 'Europe/Berlin' ? 'selected' : ''; ?>>Germany (Berlin)</option>
                            <option value="Europe/Rome" <?php echo $current_timezone === 'Europe/Rome' ? 'selected' : ''; ?>>Italy (Rome)</option>
                            <option value="Europe/Madrid" <?php echo $current_timezone === 'Europe/Madrid' ? 'selected' : ''; ?>>Spain (Madrid)</option>
                            <option value="Europe/Moscow" <?php echo $current_timezone === 'Europe/Moscow' ? 'selected' : ''; ?>>Russia (Moscow)</option>
                        </optgroup>
                        <optgroup label="Americas">
                            <option value="America/New_York" <?php echo $current_timezone === 'America/New_York' ? 'selected' : ''; ?>>USA (New York)</option>
                            <option value="America/Chicago" <?php echo $current_timezone === 'America/Chicago' ? 'selected' : ''; ?>>USA (Chicago)</option>
                            <option value="America/Los_Angeles" <?php echo $current_timezone === 'America/Los_Angeles' ? 'selected' : ''; ?>>USA (Los Angeles)</option>
                            <option value="America/Toronto" <?php echo $current_timezone === 'America/Toronto' ? 'selected' : ''; ?>>Canada (Toronto)</option>
                            <option value="America/Mexico_City" <?php echo $current_timezone === 'America/Mexico_City' ? 'selected' : ''; ?>>Mexico (Mexico City)</option>
                            <option value="America/Sao_Paulo" <?php echo $current_timezone === 'America/Sao_Paulo' ? 'selected' : ''; ?>>Brazil (São Paulo)</option>
                        </optgroup>
                        <optgroup label="Oceania">
                            <option value="Australia/Sydney" <?php echo $current_timezone === 'Australia/Sydney' ? 'selected' : ''; ?>>Australia (Sydney)</option>
                            <option value="Pacific/Auckland" <?php echo $current_timezone === 'Pacific/Auckland' ? 'selected' : ''; ?>>New Zealand (Auckland)</option>
                        </optgroup>
                        <optgroup label="Africa">
                            <option value="Africa/Cairo" <?php echo $current_timezone === 'Africa/Cairo' ? 'selected' : ''; ?>>Egypt (Cairo)</option>
                            <option value="Africa/Johannesburg" <?php echo $current_timezone === 'Africa/Johannesburg' ? 'selected' : ''; ?>>South Africa (Johannesburg)</option>
                            <option value="Africa/Lagos" <?php echo $current_timezone === 'Africa/Lagos' ? 'selected' : ''; ?>>Nigeria (Lagos)</option>
                        </optgroup>
                    </select>
                </div>
                
                <h3 style="margin-top: 30px;">Parking Rates (per hour)</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Two Wheeler Rate</label>
                        <input type="number" step="0.01" name="two_wheeler_rate" class="form-control" 
                               value="<?php echo $settings['two_wheeler_rate'] ?? TWO_WHEELER_RATE; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Four Wheeler Rate</label>
                        <input type="number" step="0.01" name="four_wheeler_rate" class="form-control" 
                               value="<?php echo $settings['four_wheeler_rate'] ?? FOUR_WHEELER_RATE; ?>" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">💾 Save Settings</button>
            </form>
            
            <div style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #ecf0f1;">
                <h3>System Information</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="label">PHP Version:</span>
                        <span class="value"><?php echo phpversion(); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">MySQL Version:</span>
                        <span class="value"><?php echo $conn->server_info; ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Server Time:</span>
                        <span class="value"><?php echo date('d-m-Y H:i:s'); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Timezone:</span>
                        <span class="value"><?php echo date_default_timezone_get(); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Current Currency:</span>
                        <span class="value"><?php 
                            $curr = getCurrencyByCode($current_currency);
                            echo "{$curr['name']} ({$curr['symbol']})";
                        ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateCurrencyPreview() {
    const select = document.getElementById('currencySelect');
    const option = select.options[select.selectedIndex];
    const text = option.text;
    document.getElementById('currencyPreview').innerHTML = 'Selected: ' + text;
}

function autoDetectCurrency() {
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '⏳ Detecting...';
    
    fetch('settings.php?action=detect_currency')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('currencySelect').value = data.currency;
                updateCurrencyPreview();
                alert('✅ Detected currency: ' + data.name + ' (' + data.symbol + ')');
            } else {
                alert('❌ Could not detect currency automatically');
            }
        })
        .catch(error => {
            alert('❌ Error detecting currency');
            console.error(error);
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '🌍 Auto-Detect';
        });
}
</script>

<?php 
$db->closeConnection();
include '../includes/footer.php'; 
?>
