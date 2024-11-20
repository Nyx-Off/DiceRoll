# 🎲 DiceRoll
> An online collaborative dice roller with real-time updates and Discord integration

[![Discord](https://img.shields.io/badge/Discord-Integration-7289DA)](https://discord.com)
[![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

## 📖 About
DiceRoll is a real-time collaborative dice rolling platform designed for tabletop RPG sessions. It features an intuitive interface that displays dice rolls, player activity, and game statistics in real-time, with optional Discord integration for sharing crucial moments with your gaming community.

## ✨ Features

### Core Features
- Multiple dice types (D4, D6, D8, D10, D12, D20, D100)
- Custom dice creation
- Multiple dice rolls simultaneously
- Bonus/malus system
- Real-time roll history
- Active player tracking
- Discord integration for sharing rolls

### User Features
- Customizable usernames
- Personal color selection
- Persistent user settings
- Real-time online status
- Discord notifications toggle

### Interface
- Clean, modern UI
- Dark theme
- Mobile-responsive design
- Real-time updates
- Session persistence

## 🚀 Installation

### Prerequisites
- PHP 7.4+
- MySQL/MariaDB
- Web server (Apache/Nginx)
- Discord webhook URL (optional)

### Database Setup
```sql
-- Create required tables
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    color VARCHAR(7) NOT NULL
);

CREATE TABLE rolls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    dice_type INT NOT NULL,
    dice_count INT DEFAULT 1,
    bonus INT DEFAULT 0,
    result INT NOT NULL,
    base_roll INT NOT NULL,
    individual_rolls JSON,
    roll_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_sessions (
    user_id INT NOT NULL,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Configuration
1. Clone the repository
2. Copy `config.php.example` to `config.php`
3. Update database credentials in `config.php`:
```php
define('DB_HOST', 'your-host');
define('DB_NAME', 'your-db');
define('DB_USER', 'your-user');
define('DB_PASS', 'your-password');
```
4. Configure Discord webhook in `discord_webhook.php` (optional)

## 💻 Technical Details

### Architecture
- **Backend**: PHP 7.4+ with PDO
- **Database**: MySQL/MariaDB
- **Frontend**: Vanilla JavaScript
- **Styling**: Modern CSS3 with CSS Variables
- **Real-time**: AJAX polling

### File Structure
```
├── active_users.php    # User session management
├── config.php         # Database configuration
├── discord_webhook.php # Discord integration
├── get_history.php    # Roll history retrieval
├── get_online_users.php # Active users list
├── index.php         # Main application
├── roll.php         # Dice rolling logic
└── settings.php    # User settings management
```

## 🔒 Security Considerations
- SQL injection protection via PDO prepared statements
- Session-based authentication
- XSS prevention through output escaping
- Rate limiting on roll submissions
- Secure database credentials handling

## 📝 License
MIT License - See [LICENSE](LICENSE) for details

---
Made with passion for the TTRPG community 🎭
