<?php
session_start();
require_once 'config.php';
require_once 'discord_webhook.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        die(json_encode(['success' => false]));
    }

    $type = intval($_POST['type']);
    $count = intval($_POST['count']) ?: 1; // Nombre de dés à lancer
    $bonus = intval($_POST['bonus']);
    $sendToDiscord = isset($_POST['sendToDiscord']) && $_POST['sendToDiscord'] === 'true';
    
    $rolls = []; // Stocker chaque résultat individuel
    $base_total = 0;
    
    // Effectuer chaque lancer
    for ($i = 0; $i < $count; $i++) {
        $roll = rand(1, $type);
        $rolls[] = $roll;
        $base_total += $roll;
    }
    
    $final_result = $base_total + $bonus;
    
    try {
        $stmt = $pdo->prepare("INSERT INTO rolls (user_id, dice_type, dice_count, bonus, result, base_roll, individual_rolls) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_SESSION['user_id'], 
            $type, 
            $count,
            $bonus, 
            $final_result, 
            $base_total,
            json_encode($rolls)
        ]);
        
        if ($sendToDiscord) {
            sendDiscordWebhook(
                $_SESSION['username'],
                $_SESSION['color'],
                $type,
                $count,
                $rolls,
                $bonus,
                $final_result
            );
        }
        
        echo json_encode([
            'success' => true,
            'rolls' => $rolls,
            'base_total' => $base_total,
            'bonus' => $bonus,
            'final_result' => $final_result
        ]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false]);
    }
}