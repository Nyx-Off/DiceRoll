<?php
// get_history.php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    die("Non autorisé");
}

$stmt = $pdo->query("SELECT users.username, users.color, rolls.dice_type, rolls.bonus, 
                     rolls.result, rolls.base_roll, rolls.roll_time, 
                     COALESCE(rolls.dice_count, 1) as dice_count,
                     rolls.individual_rolls
                     FROM rolls 
                     JOIN users ON rolls.user_id = users.id 
                     ORDER BY rolls.id DESC 
                     LIMIT 20");

while ($roll = $stmt->fetch()) {
    $individual_rolls = json_decode($roll['individual_rolls'] ?? '[]', true) ?: [$roll['base_roll']];
    $rolls_text = implode(', ', $individual_rolls);
    
    echo "<div class='roll-result'>";
    echo "<div class='roll-details'>";
    echo "<span class='username' style='color: " . htmlspecialchars($roll['color']) . "'>" 
         . htmlspecialchars($roll['username']) . "</span>";
    echo "<span class='roll-info'>" . $roll['dice_count'] . "D" . $roll['dice_type'] . " → </span>";
    echo "<span class='roll-value'>[" . $rolls_text . "]";
    
    // Toujours afficher la somme des dés
    if (count($individual_rolls) > 1) {
        echo " = " . array_sum($individual_rolls);
    }
    echo "</span>";
    
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