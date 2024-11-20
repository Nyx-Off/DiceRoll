<?php
// discord_webhook.php
function sendDiscordWebhook($username, $userColor, $diceType, $diceCount, $rolls, $bonus, $finalResult) {
    $webhookUrl = "";
    
    $rollsText = implode(', ', $rolls);
    $bonusText = $bonus > 0 ? "+$bonus" : ($bonus < 0 ? $bonus : '');
    $calculation = $bonus != 0 ? "[$rollsText] $bonusText = $finalResult" : "[$rollsText] = $finalResult";
    
    $embed = [
        "title" => "Nouveau lancer de dé",
        "color" => hexdec(str_replace('#', '', $userColor)),
        "fields" => [
            [
                "name" => "Joueur",
                "value" => $username,
                "inline" => true
            ],
            [
                "name" => "Dé",
                "value" => "${diceCount}D$diceType",
                "inline" => true
            ],
            [
                "name" => "Résultat",
                "value" => $calculation,
                "inline" => true
            ]
        ],
        "timestamp" => date("c")
    ];
    
    $data = ["embeds" => [$embed]];
    
    $ch = curl_init($webhookUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response !== false;
}
