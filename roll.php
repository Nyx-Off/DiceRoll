<?php
// roll.php
session_start();
require_once 'config.php';
require_once 'discord_webhook.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        die(json_encode(['success' => false]));
    }

    $type = intval($_POST['type']);
    $bonus = intval($_POST['bonus']);
    $sendToDiscord = isset($_POST['sendToDiscord']) && $_POST['sendToDiscord'] === 'true';
    
    $base_roll = rand(1, $type);
    $final_result = $base_roll + $bonus;
    
    try {
        $stmt = $pdo->prepare("INSERT INTO rolls (user_id, dice_type, bonus, result, base_roll) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $type, $bonus, $final_result, $base_roll]);
        
        if ($sendToDiscord) {
            sendDiscordWebhook(
                $_SESSION['username'],
                $_SESSION['color'],
                $type,
                $base_roll,
                $bonus,
                $final_result
            );
        }
        
        echo json_encode([
            'success' => true,
            'base_roll' => $base_roll,
            'bonus' => $bonus,
            'final_result' => $final_result
        ]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false]);
    }
}
?>