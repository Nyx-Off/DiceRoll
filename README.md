# 🎲 DiceRoll

> Un lanceur de dés collaboratif en temps réel pour vos parties de jeu de rôle !

[![Discord](https://img.shields.io/badge/Discord-Integration-7289DA)](https://discord.com)
[![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)


## 📖 À propos

DiceRoll est né de la passion pour les jeux de rôle et de la nécessité de maintenir cette connexion si particulière entre joueurs, même à distance. Dans un monde où le jeu en ligne devient de plus en plus présent, nous avions besoin d'un outil qui ne se contente pas simplement de lancer des dés, mais qui recrée cette ambiance si spéciale de la table de jeu.

Cette application web permet aux joueurs de se retrouver dans un espace virtuel où chaque lancer de dés devient un moment partagé. L'interface intuitive et élégante affiche en temps réel les résultats de chaque participant, leurs bonus et malus, le tout dans une atmosphère personnalisée grâce aux couleurs distinctives de chaque joueur.

L'intégration avec Discord pousse l'expérience encore plus loin, permettant de partager instantanément les moments cruciaux de vos parties avec votre communauté. Que ce soit pour ce jet de sauvegarde décisif ou ce coup critique tant attendu, vos lancers peuvent être automatiquement partagés sur votre serveur Discord.

## ✨ Fonctionnalités

- 🎯 Large choix de dés (D4, D6, D8, D10, D12, D20, D100)
- 🛠 Création de dés personnalisés
- 📜 Historique des lancers en temps réel
- 👥 Suivi des joueurs connectés
- ➕ Système de bonus/malus
- 🤖 Intégration Discord
- 🎨 Personnalisation des couleurs par joueur
- 📱 Design responsive

## 🚀 Pour commencer

### Prérequis

- Serveur web avec PHP 7.4 ou supérieur
- Base de données MySQL
- URL webhook Discord (optionnel)

### Installation

1. Clonez le dépôt
```bash
git clone https://github.com/votre-username/DiceRoll.git
```

2. Créez une base de données MySQL

3. Configurez vos accès dans `config.php`
```php
define('DB_HOST', 'votre-host');
define('DB_NAME', 'votre-db');
define('DB_USER', 'votre-user');
define('DB_PASS', 'votre-password');
```

4. Configurez votre webhook Discord dans `discord_webhook.php` (optionnel)

## 💻 Stack technique

- **Backend**: PHP 7.4+
- **Base de données**: MySQL
- **Frontend**: JavaScript vanilla
- **Styles**: CSS3 moderne
- **Interface**: HTML5

## 🔒 Sécurité

N'oubliez pas de :
- Protéger votre fichier `config.php`
- Modifier les identifiants par défaut
- Configurer correctement les permissions de votre serveur

## 🤝 Contribution

Les contributions sont les bienvenues ! N'hésitez pas à :
- 🐛 Signaler des bugs
- 💡 Proposer des améliorations
- 🔧 Soumettre des pull requests

## 📝 Licence

MIT License - Voir le fichier [LICENSE](LICENSE) pour plus de détails

---

<p align="center">
Fait avec ❤️ pour MOI lol
</p>
