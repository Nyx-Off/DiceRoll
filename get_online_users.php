<?php
// get_online_users.php
session_start();
require_once 'active_users.php';

$activeUsers = getActiveUsers();
foreach ($activeUsers as $user) {
    echo "<div class='online-user'>";
    echo "<span style='color: " . htmlspecialchars($user['color']) . "'>" 
         . htmlspecialchars($user['username']) . "</span>";
    echo "</div>";
}
?>