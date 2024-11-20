<?php
// index.php
session_start();
require_once 'config.php';
require_once 'active_users.php';

// Si l'utilisateur est connecté, mettre à jour son activité
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    if (!$stmt->fetch()) {
        // L'utilisateur n'existe plus dans la base de données
        session_destroy();
        header("Location: index.php");
        exit;
    } else {
        updateUserActivity($_SESSION['user_id']);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>DiceRoll</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --background: #1a1a1a;
            --surface: #2d2d2d;
            --primary: #6366f1;
            --text: #e1e1e1;
            --text-secondary: #a1a1a1;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background);
            color: var(--text);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 20px;
        }

        .login-container {
            background-color: var(--surface);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            margin: 100px auto;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .login-form input {
            padding: 0.75rem;
            border: 1px solid #444;
            border-radius: 6px;
            background-color: #333;
            color: var(--text);
        }

        .color-picker {
            width: 100%;
            height: 40px;
            padding: 5px;
            border-radius: 6px;
            background-color: #333;
            border: 1px solid #444;
        }

        .dice-section {
            background-color: var(--surface);
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .dice-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 10px;
            margin-bottom: 1rem;
        }

        .dice-button {
            padding: 1rem;
            border: 2px solid var(--primary);
            background-color: transparent;
            color: var(--text);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dice-button.selected {
            background-color: var(--primary);
            color: white;
        }

        .roll-button {
            width: 100%;
            padding: 1rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .roll-button:hover {
            background-color: #5558ee;
        }

        .roll-button:disabled {
            background-color: #4b4b4b;
            cursor: not-allowed;
        }

        .history {
            background-color: var(--surface);
            padding: 1.5rem;
            border-radius: 12px;
        }

        .roll-result {
            margin: 10px 0;
            padding: 1rem;
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .roll-details {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .roll-value {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .bonus {
            color: #4CAF50;
        }

        .malus {
            color: #f44336;
        }

        .online-users {
            background-color: var(--surface);
            padding: 1.5rem;
            border-radius: 12px;
            position: sticky;
            top: 20px;
        }

        .online-user {
            padding: 0.75rem;
            margin: 5px 0;
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 6px;
        }

        .controls {
            display: flex;
            gap: 10px;
            align-items: center;
            margin: 1rem 0;
        }

        .bonus-input {
            padding: 0.75rem;
            border-radius: 6px;
            background-color: #333;
            border: 1px solid #444;
            color: var(--text);
            width: 100px;
        }

        h2, h3 {
            color: var(--text);
            margin-top: 0;
            margin-bottom: 1.5rem;
        }

        .roll-time {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }

            .online-users {
                position: static;
            }
        }

        .discord-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text);
            cursor: pointer;
        }

        .discord-toggle input {
            width: 16px;
            height: 16px;
        }

        .user-settings {
            background-color: var(--surface);
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .settings-form {
            display: flex;
            gap: 10px;
        }

        .settings-form input[type="text"] {
            padding: 0.75rem;
            border: 1px solid #444;
            border-radius: 6px;
            background-color: #333;
            color: var(--text);
            flex: 1;
        }
    </style>
</head>
<body>
    <?php
    // Gestion de la connexion
    if (!isset($_SESSION['user_id']) && isset($_POST['username'])) {
        // D'abord, vérifions si l'utilisateur existe déjà
        $stmt = $pdo->prepare("SELECT id, color FROM users WHERE username = ?");
        $stmt->execute([$_POST['username']]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Si l'utilisateur existe, on met à jour sa couleur
            $stmt = $pdo->prepare("UPDATE users SET color = ? WHERE id = ?");
            $stmt->execute([$_POST['color'], $user['id']]);
        } else {
            // Si l'utilisateur n'existe pas, on le crée
            $stmt = $pdo->prepare("INSERT INTO users (username, color) VALUES (?, ?)");
            $stmt->execute([$_POST['username'], $_POST['color']]);
            
            // On récupère l'ID du nouvel utilisateur
            $user = array(
                'id' => $pdo->lastInsertId(),
                'color' => $_POST['color']
            );
        }
        
        // Dans tous les cas, on met à jour la session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['color'] = $_POST['color'];
    }

    // Affichage du formulaire de connexion si non connecté
    if (!isset($_SESSION['user_id'])) {
    ?>
        <div class="login-container">
            <h2>Entrez votre pseudo</h2>
            <form method="POST" class="login-form">
                <input type="text" name="username" required placeholder="Votre pseudo">
                <input type="color" name="color" class="color-picker" value="#6366f1">
                <button type="submit" class="roll-button">Commencer</button>
            </form>
        </div>
    <?php
    } else {
    ?>
        <div class="container">
            <main>
                <div class="dice-section">
                    <h2>Bienvenue <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
                    <div class="dice-grid">
                        <?php
                        $dice_types = [4, 6, 8, 10, 12, 20, 100];
                        foreach ($dice_types as $type) {
                            echo "<button class='dice-button' data-type='$type'>D$type</button>";
                        }
                        ?>
                        <input type="number" id="custom-dice" min="1" placeholder="Dé personnalisé" class="bonus-input">
                    </div>
                    <label class="discord-toggle">
                        <input type="checkbox" id="send-to-discord" checked>
                        <span>Envoyer sur Discord</span>
                    </label>    
                    <span>Nombre de Dés</span>
                    <input type="number" id="dice-count" class="bonus-input" value="1" min="1" placeholder="Nombre de dés">
                    <span>          Bonus / Malus</span>
                    <input type="number" id="bonus" class="bonus-input" value="0" placeholder="Bonus/Malus">
                    <div class="controls">
                        
                        <button id="roll-button" class="roll-button" disabled>Lancer le dé</button>
                    </div>
                </div>
                <div class="user-settings">
                    <h3>Paramètres</h3>
                    <form id="settings-form" class="settings-form">
                        <input type="text" id="new-username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
                        <input type="color" id="new-color" value="<?php echo htmlspecialchars($_SESSION['color']); ?>">
                        <button type="submit" class="roll-button">Mettre à jour</button>
                    </form>
                </div>
                <div class="history">
                    <h3>Historique des lancers</h3>
                    <div id="roll-history"></div>
                </div>
            </main>

            <aside>
                <div class="online-users">
                    <h3>Joueurs en ligne</h3>
                    <div id="active-users-list"></div>
                </div>
            
            </aside>
        </div>

        <script>
            let selectedDiceType = null;

            // Gestion de la sélection des dés
            document.querySelectorAll('.dice-button').forEach(button => {
                button.addEventListener('click', () => {
                    document.querySelectorAll('.dice-button').forEach(b => b.classList.remove('selected'));
                    button.classList.add('selected');
                    selectedDiceType = parseInt(button.dataset.type);
                    document.getElementById('roll-button').disabled = false;
                });
            });

            // Gestion du dé personnalisé
            document.getElementById('custom-dice').addEventListener('input', (e) => {
                const value = parseInt(e.target.value);
                if (value && value > 0) {
                    selectedDiceType = value;
                    document.querySelectorAll('.dice-button').forEach(b => b.classList.remove('selected'));
                    document.getElementById('roll-button').disabled = false;
                } else {
                    document.getElementById('roll-button').disabled = true;
                }
            });

            // Gestion du lancer de dé
            document.getElementById('roll-button').addEventListener('click', () => {
                if (selectedDiceType) {
                    const bonus = parseInt(document.getElementById('bonus').value) || 0;
                    const count = parseInt(document.getElementById('dice-count').value) || 1;
                    saveDiceRoll(selectedDiceType, count, bonus);
                }
            });

            function saveDiceRoll(type, count, bonus) {
                const sendToDiscord = document.getElementById('send-to-discord').checked;
                fetch('roll.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `type=${type}&count=${count}&bonus=${bonus}&sendToDiscord=${sendToDiscord}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateHistory();
                    }
                });
            }

            function updateHistory() {
                fetch('get_history.php')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('roll-history').innerHTML = html;
                });
            }

            function updateOnlineUsers() {
                fetch('get_online_users.php')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('active-users-list').innerHTML = html;
                });
            }

            // Mettre à jour l'historique et les utilisateurs en ligne périodiquement
            setInterval(updateHistory, 1000);
            setInterval(updateOnlineUsers, 3000);

            // Mise à jour initiale
            updateHistory();
            updateOnlineUsers();

            document.getElementById('settings-form').addEventListener('submit', (e) => {
                e.preventDefault();
                const newUsername = document.getElementById('new-username').value;
                const newColor = document.getElementById('new-color').value;

                fetch('settings.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `username=${encodeURIComponent(newUsername)}&color=${encodeURIComponent(newColor)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateHistory();
                        updateOnlineUsers();
                    } else {
                        alert(data.message || 'Erreur lors de la mise à jour');
                    }
                });
            });
        </script>
    <?php
    }
    ?>
</body>
</html>