<?php
// active_users.php
require_once 'config.php';

// Fonction pour mettre à jour ou ajouter un utilisateur actif
function updateUserActivity($userId) {
    global $pdo;
    
    try {
        // Vérifier d'abord si l'utilisateur existe dans la table users
        $checkUser = $pdo->prepare("SELECT id FROM users WHERE id = ?");
        $checkUser->execute([$userId]);
        if (!$checkUser->fetch()) {
            // Si l'utilisateur n'existe pas, on ne fait rien
            return false;
        }

        // Supprimer les anciennes sessions (plus de 5 minutes d'inactivité)
        $stmt = $pdo->prepare("DELETE FROM user_sessions WHERE last_activity < DATE_SUB(NOW(), INTERVAL 5 MINUTE)");
        $stmt->execute();
        
        // Vérifier si l'utilisateur existe déjà dans user_sessions
        $stmt = $pdo->prepare("SELECT user_id FROM user_sessions WHERE user_id = ?");
        $stmt->execute([$userId]);
        
        if ($stmt->fetch()) {
            // Mettre à jour l'activité si l'utilisateur existe
            $stmt = $pdo->prepare("UPDATE user_sessions SET last_activity = NOW() WHERE user_id = ?");
            $stmt->execute([$userId]);
        } else {
            // Insérer une nouvelle entrée si l'utilisateur n'existe pas
            $stmt = $pdo->prepare("INSERT INTO user_sessions (user_id, last_activity) VALUES (?, NOW())");
            $stmt->execute([$userId]);
        }
        return true;
    } catch(PDOException $e) {
        error_log("Erreur dans updateUserActivity: " . $e->getMessage());
        return false;
    }
}

// Fonction pour obtenir la liste des utilisateurs actifs
function getActiveUsers() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("SELECT DISTINCT u.username, u.color 
                            FROM user_sessions us 
                            JOIN users u ON us.user_id = u.id 
                            WHERE us.last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Erreur dans getActiveUsers: " . $e->getMessage());
        return array();
    }
}