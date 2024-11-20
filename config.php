<?php
// config.php - Configuration de la base de données
define('DB_HOST', 'zy16r.myd.infomaniak.com');
define('DB_NAME', 'zy16r_dice_roller');
define('DB_USER', 'zy16r_system');
define('DB_PASS', 'SamyBensalem@2024');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
