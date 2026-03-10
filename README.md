# Service Desk - Enterprise Multi-Tenant Ticketing System

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel 12">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css" alt="Tailwind CSS 3">
  <img src="https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js" alt="Alpine.js">
  <img src="https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php" alt="PHP 8.3+">
</p>

## Enterprise-Grade Multi-Tenant Service Desk Solution

Service Desk is a modern, scalable **multi-tenant ticketing system** built with the latest web technologies. Designed for Managed Service Providers (MSPs), IT departments, and enterprise organizations managing multiple customers or departments.

---

## Technology Stack

| Component | Technology |
|-----------|------------|
| **Framework** | Laravel 12.x |
| **Authentication** | Laravel Breeze |
| **Frontend** | Blade Templates + Alpine.js |
| **Styling** | Tailwind CSS 3.x |
| **Database** | MySQL / MariaDB |
| **PHP Version** | 8.3+ |

---

## Key Features

### ✅ Implemented

- **Multi-Tenant Architecture** - Isolated data per tenant with shared infrastructure
- **Role-Based Access Control (RBAC)**:
  - Super Admin (System Owner)
  - Tenant Admin
  - Agents
  - Requesters
  - Auditors
- **Ticket Management**:
  - Create, view, update, and close tickets
  - Status workflow (New → Triaged → In Progress → Pending → Resolved → Closed)
  - Priority levels (Critical, High, Medium, Low)
  - Impact assessment
  - Queue-based routing
  - Ticket assignment (agents & groups)
- **Queue Management** - Organize tickets by department/team
- **Group Management** - Assign agents to teams
- **User Management** - Tenant-specific user administration
- **Dashboard** - Real-time overview of ticket statistics
- **Filtering & Search** - Filter tickets by status, priority, and queue
- **Email Notifications** - Ready to configure notification system

### 🔧 Prepared (CRUD Views Ready)

- **SLA Policies** - Define response and resolution times per priority
- **Business Hours** - Configure support hour calendars
- **Holiday Calendars** - Manage non-working days
- **Knowledge Base** - Article management system
- **Asset Management (CMDB)** - Track company assets
- **Automation Rules** - Workflow automation triggers

### 🚧 Prepared but Not Configured

- **Auto Ticket Assignment** - Automatic distribution based on queue rules
- **SLA Breach Notifications** - Automated alerts for SLA violations
- **Email-to-Ticket** - Create tickets from incoming emails

---

## Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                     Super Admin                              │
│                  (superadmin@system.com)                    │
└─────────────────────────────────────────────────────────────┘
                            │
                            │ Creates
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                     Tenants                                 │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐    │
│  │   Tenant 1   │  │   Tenant 2   │  │   Tenant N   │    │
│  │              │  │              │  │              │    │
│  │ - Admin     │  │ - Admin     │  │ - Admin     │    │
│  │ - Agents    │  │ - Agents    │  │ - Agents    │    │
│  │ - Requesters│  │ - Requesters│  │ - Requesters│    │
│  │ - Queues    │  │ - Queues    │  │ - Queues    │    │
│  │ - Groups    │  │ - Groups    │  │ - Groups    │    │
│  │ - Tickets   │  │ - Tickets   │  │ - Tickets   │    │
│  └──────────────┘  └──────────────┘  └──────────────┘    │
└─────────────────────────────────────────────────────────────┘
```

---

## Getting Started

### Prerequisites

- PHP 8.3+
- Composer
- Node.js 18+
- MySQL 8.0+ or MariaDB 10.5+

### Installation

```bash
# 1. Clone the repository
git clone <repository-url>
cd service-desk

# 2. Install PHP dependencies
composer install

# 3. Install Node.js dependencies
npm install

# 4. Build assets
npm run dev

# 5. Configure environment
cp .env.example .env
# Edit .env with your database credentials

# 6. Generate application key
php artisan key:generate

# 7. Run migrations
php artisan migrate

# 8. Seed the database (creates Super Admin)
php artisan db:seed

# 9. Start the development server
php artisan serve
```

### Default Credentials

After seeding, you can log in with:

- **Email:** superadmin@system.com
- **Password:** superadmin123

---

## Future Enhancements 🚀

We can implement additional features based on your needs:

### 🔐 Authentication & Security
- **Single Sign-On (SSO)** - SAML2, OAuth2 integration
- **Microsoft Graph Login** - Azure AD authentication
- **Google Workspace Login** - G-Suite integration
- **Two-Factor Authentication (2FA)** - TOTP-based verification
- **LDAP/Active Directory** - Corporate directory sync
- **IP Whitelisting** - Restrict access by IP

### 🌐 Multi-Tenancy & Domains
- **Custom Subdomains** - `company.service-desk.com`
- **Custom Domains** - `support.yourcompany.com`
- **White-Labeling** - Custom branding per tenant
- **Tenant-Specific Themes** - Custom colors and logos

### 📊 Reporting & Analytics
- **Advanced Dashboards** - Custom KPI widgets
- **SLA Reporting** - Breach analysis and trends
- **Agent Performance** - Response times, resolution rates
- **Export to PDF/Excel** - Custom report generation

### 🤖 Automation & AI
- **Ticket Auto-Assignment** - Round-robin, load balancing, skills-based
- **SLA Automation** - Auto-escalation, breach warnings
- **Canned Responses** - Quick reply templates
- **AI Ticket Classification** - Auto-categorization
- **Chatbot Integration** - AI-powered first-level support

### 📧 Communication
- **Email-to-Ticket** - Create tickets from emails
- **Internal Notifications** - In-app messaging
- **SMS Notifications** - Critical alerts via SMS
- **Slack/Teams Integration** - Notifications to chat platforms
- **Webhooks** - Third-party integrations

### 🔧 ITSM Features
- **Problem Management** - Root cause analysis
- **Change Management** - IT change approval workflows
- **Asset Lifecycle** - Full asset management
- **Contract Management** - Vendor & SLA contracts
- **Knowledge Base** - Public-facing portal

### 📱 Mobile & UX
- **Mobile App** - iOS and Android native apps
- **PWA Support** - Progressive Web App
- **Dark Mode** - System preference detection
- **RTL Support** - Right-to-left languages

---

## Roadmap

- [ ] Email integration (IMAP/POP3)
- [ ] WebSocket real-time updates
- [ ] Advanced reporting module
- [ ] Mobile application
- [ ] SSO providers (Azure AD, Google, Okta)
- [ ] Custom domain support

---

## Support & Contact

This is a **foundation project** - we can customize and extend it to match your specific requirements.

**For implementation support, custom development, or enterprise features, please contact:**

📧 **Email:** [dafos@protonmail.com]

---

## License

This project is proprietary software. All rights reserved.

---

## Acknowledgments

Built with Laravel, Tailwind CSS, and Alpine.js - modern tools for modern applications.
