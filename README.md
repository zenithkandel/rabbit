<p align="center">
  <img src="https://img.shields.io/badge/version-1.0.0-B94A2C?style=flat-square" alt="Version">
  <img src="https://img.shields.io/badge/license-MIT-5C6B55?style=flat-square" alt="License">
  <img src="https://img.shields.io/badge/php-%3E%3D8.0-777BB4?style=flat-square" alt="PHP">
  <img src="https://img.shields.io/badge/status-active-5C6B55?style=flat-square" alt="Status">
</p>

<h1 align="center">🐰 Rabbit</h1>

<p align="center">
  <strong>A minimalist, self-hosted notification service for developers</strong>
  <br>
  <em>Send notifications from any app with a simple API call</em>
</p>

<p align="center">
  <a href="#features">Features</a> •
  <a href="#quick-start">Quick Start</a> •
  <a href="#api-reference">API Reference</a> •
  <a href="#self-hosting">Self-Hosting</a> •
  <a href="#contributing">Contributing</a>
</p>

---

## ✨ Features

- **🚀 Simple API** — Send notifications with a single HTTP request
- **📱 Multi-App Support** — Connect unlimited apps with unique API keys
- **🎨 Beautiful Dashboard** — Clean, retro-inspired interface for managing everything
- **🔍 Smart Filtering** — Search, filter by app, date range, and notification type
- **🌙 Dark Mode** — Easy on the eyes, day or night
- **📦 Self-Hosted** — Your data stays on your server
- **⚡ Lightweight** — No heavy frameworks, just vanilla PHP and JavaScript

## 📸 Screenshots

<table>
  <tr>
    <td><strong>Landing Page</strong></td>
    <td><strong>Dashboard</strong></td>
  </tr>
  <tr>
    <td><img src="docs/screenshots/landing.png" alt="Landing Page" width="400"></td>
    <td><img src="docs/screenshots/dashboard.png" alt="Dashboard" width="400"></td>
  </tr>
</table>

## 🚀 Quick Start

### Prerequisites

- PHP 8.0 or higher
- Web server (Apache, Nginx, or similar)
- MySQL 5.7+ (optional, for persistent storage)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/rabbit.git
   cd rabbit
   ```

2. **Configure your web server**
   
   Point your web server's document root to the `rabbit` directory.

3. **Set up the database** (optional)
   ```bash
   mysql -u root -p < database/schema.sql
   ```

4. **Configure environment**
   ```bash
   cp .env.example .env
   # Edit .env with your settings
   ```

5. **Access the dashboard**
   
   Navigate to `http://localhost/rabbit` in your browser.

## 📡 API Reference

### Send a Notification

```bash
POST /api/notify
```

**Headers:**
| Header | Type | Description |
|--------|------|-------------|
| `X-API-Key` | string | Your app's API key |
| `Content-Type` | string | `application/json` |

**Body Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `title` | string | Yes | Notification title |
| `message` | string | Yes | Notification body |
| `type` | string | No | `info`, `success`, `warning`, `error` |
| `target_link` | string | No | URL for more details about the notification |
| `icon` | string | No | Custom icon URL |

**Example Request:**

```javascript
const response = await fetch('https://your-domain.com/api/notify', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-API-Key': 'your_api_key_here'
  },
  body: JSON.stringify({
    title: 'New Order',
    message: 'Order #1234 has been placed',
    type: 'success',
    target_link: 'https://your-app.com/orders/1234'
  })
});
```

**Response:**

```json
{
  "success": true,
  "id": "notif_abc123xyz",
  "message": "Notification delivered successfully"
}
```

### Get All Notifications

```bash
GET /api/notifications
```

### Get App Statistics

```bash
GET /api/apps/:appId/stats
```

## 🏗️ Project Structure

```
rabbit/
├── API/                    # API endpoints
│   └── notify.php
├── assets/                 # Static assets (images, icons)
├── CSS/                    # Global stylesheets
│   ├── global.css
│   └── index.css
├── JS/                     # Global JavaScript
│   ├── global.js
│   └── index.js
├── dashboard/              # Dashboard application
│   ├── CSS/               # Dashboard styles
│   ├── JS/                # Dashboard scripts
│   ├── index.php          # Dashboard shell
│   ├── home.php           # Overview page
│   ├── notifications.php  # Notifications list
│   ├── apps.php           # Connected apps
│   ├── connect.php        # Integration guide
│   └── settings.php       # User settings
├── database/              # Database migrations
├── docs/                  # Documentation
├── index.php              # Landing page
├── LICENSE                # MIT License
└── README.md
```

## 🎨 Design System

Rabbit uses a warm, editorial-inspired design system:

| Token | Color | Usage |
|-------|-------|-------|
| **Cream** | `#F7F4EE` | Background |
| **Ink** | `#1C1917` | Primary text |
| **Rust** | `#B94A2C` | Accent, warnings |
| **Sage** | `#5C6B55` | Success, secondary |
| **Paper** | `#FFFEF9` | Cards, elevated surfaces |

**Typography:**
- **Display:** Playfair Display
- **Body:** Inter
- **Code:** JetBrains Mono

## 🔧 Configuration

### Environment Variables

| Variable | Default | Description |
|----------|---------|-------------|
| `DB_HOST` | `localhost` | Database host |
| `DB_NAME` | `rabbit` | Database name |
| `DB_USER` | `root` | Database user |
| `DB_PASS` | `` | Database password |
| `APP_URL` | `http://localhost` | Application URL |
| `APP_ENV` | `development` | Environment mode |

## 🛡️ Security

- API keys are hashed before storage
- Rate limiting prevents abuse
- CORS headers are configurable
- Input validation on all endpoints
- XSS protection enabled

## 🤝 Contributing

Contributions are welcome! Please read our [Contributing Guide](CONTRIBUTING.md) for details.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📋 Roadmap

- [ ] Push notification support (Web Push API)
- [ ] Email notification channel
- [ ] Slack/Discord integrations
- [ ] Notification templates
- [ ] Team/organization support
- [ ] Mobile app (React Native)
- [ ] Webhook callbacks
- [ ] Analytics dashboard

## 📄 License

This project is licensed under the MIT License — see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- [Playfair Display](https://fonts.google.com/specimen/Playfair+Display) by Claus Eggers Sørensen
- [Inter](https://rsms.me/inter/) by Rasmus Andersson
- [JetBrains Mono](https://www.jetbrains.com/lp/mono/) by JetBrains

---

<p align="center">
  <sub>Built with ☕ and minimalism in mind</sub>
  <br>
  <sub>© 2026 Rabbit. All rights reserved.</sub>
</p>
