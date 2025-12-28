<?php

require_once '../../includes/session/session.php';
require_once '../../includes/helpers.php';
require_once '../../includes/db_connection.php';

requireAdmin();

$db = new DBConnection();
$stats = $db->getAdminStats();

$usersCount = 0;
$productsCount = 0;
$bookingsCount = 0;
$monthlyRevenue = '0,00';

if (is_array($stats)) {
    $usersCount = (int) ($stats['total_users'] ?? 0);
    $productsCount = (int) ($stats['active_products'] ?? 0);
    $bookingsCount = (int) ($stats['active_bookings'] ?? 0);
    $monthlyRevenue = number_format((float) ($stats['monthly_revenue'] ?? 0), 2, ',', '.');
}

$html = buildPage('../pages/admin.html', $_SERVER['PHP_SELF']);
$html = str_replace(
    ['[STAT_USERS]', '[STAT_PRODUCTS]', '[STAT_BOOKINGS]', '[STAT_REVENUE]'],
    [
        htmlspecialchars((string)$usersCount),
        htmlspecialchars((string)$productsCount),
        htmlspecialchars((string)$bookingsCount),
        htmlspecialchars($monthlyRevenue)
    ],
    $html
);

echo $html;
?>
