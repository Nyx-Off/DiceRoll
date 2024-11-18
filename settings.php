<?php
// settings.php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        die(json_encode(['success' => false, 'message' => 'Non autorisé']));
    }

    $newUsername = trim($_POST['username']);
    $newColor = $_POST['color'];

    // Vérifier si le nouveau pseudo est déjà pris (sauf par l'utilisateur actuel)
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $stmt->execute([$newUsername, $_SESSION['user_id']]);
    if ($stmt->fetch()) {
        die(json_encode(['success' => false, 'message' => 'Ce pseudo est déjà pris']));
    }

    try {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, color = ? WHERE id = ?");
        $stmt->execute([$newUsername, $newColor, $_SESSION['user_id']]);
        
        // Mettre à jour la session
        $_SESSION['username'] = $newUsername;
        $_SESSION['color'] = $newColor;
        
        echo json_encode(['success' => true]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
    }
}