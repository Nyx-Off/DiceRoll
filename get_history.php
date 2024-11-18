<?php
// get_history.php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    die("Non autorisé");
}

$stmt = $pdo->query("SELECT users.username, users.color, rolls.dice_type, rolls.bonus, rolls.result, rolls.base_roll, rolls.roll_time 
                     FROM rolls 
                     JOIN users ON rolls.user_id = users.id 
                     ORDER BY rolls.id DESC 
                     LIMIT 20");

while ($roll = $stmt->fetch()) {
    echo "<div class='roll-result'>";
    echo "<div class='roll-details'>";
    echo "<span class='username' style='color: " . htmlspecialchars($roll['color']) . "'>" 
         . htmlspecialchars($roll['username']) . "</span>";
    echo "<span class='roll-info'>D" . $roll['dice_type'] . " →</span>";
    echo "<span class='roll-value'>" . $roll['base_roll'] . "</span>";
    
    if ($roll['bonus'] != 0) {
        $bonusClass = $roll['bonus'] > 0 ? 'bonus' : 'malus';
        echo "<span class='$bonusClass'>(" . ($roll['bonus'] > 0 ? '+' : '') . $roll['bonus'] . ")</span>";
        echo "<span class='roll-value'>= " . $roll['result'] . "</span>";
    }
    
    echo "<span class='roll-time'>" . date('H:i:s', strtotime($roll['roll_time'])) . "</span>";
    echo "</div>";
    echo "</div>";
}
?>