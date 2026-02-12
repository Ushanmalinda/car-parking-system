<?php
require_once __DIR__ . '/../includes/functions.php';
$flash = getFlashMessage();

// Get system currency for JavaScript
$system_currency = 'USD';
try {
    $temp_db = new Database();
    $system_currency = getSystemCurrency($temp_db);
    $temp_db->closeConnection();
} catch (Exception $e) {
    // Use default if database not available
    $system_currency = 'USD';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/style.css">
    <meta name="currency" content="<?php echo $system_currency; ?>" data-currency="<?php echo $system_currency; ?>">
    <?php if (isset($extra_css)) echo $extra_css; ?>
</head>
<body data-currency="<?php echo $system_currency; ?>">
    <?php if ($flash): ?>
    <div class="flash-message <?php echo $flash['type']; ?>" id="flashMessage">
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
    <?php endif; ?>
