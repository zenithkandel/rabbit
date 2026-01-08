# DETAILS.md — Rabbit Frontend Technical Specification

> **Scope:** Frontend/UI functionality only. No backend implementation.  
> **Version:** 1.0.0  
> **Last Updated:** January 8, 2026

---

## 1. System Overview

| Aspect | Description |
|--------|-------------|
| **Architecture** | SPA-like dashboard shell with iframe-loaded child pages |
| **Routing** | Parent shell (`dashboard/index.php`) manages iframe `src` for navigation |
| **State Persistence** | `sessionStorage` stores current page; `localStorage` stores user data |
| **Theme System** | Dark/light mode via `data-theme` attribute; syncs parent ↔ iframe |
| **Toast System** | Global `window.Rabbit.Toast` for success/error notifications |
| **Design Tokens** | CSS variables in `global.css` (colors, spacing, typography) |
| **Typography** | Playfair Display (headings), Inter (body), JetBrains Mono (code) |
| **Component Pattern** | BEM naming convention with `is-*` state classes |
| **Animation** | CSS transitions via `--duration-fast` / `--duration-normal` |
| **Responsive** | Mobile-first with breakpoints at 640px, 768px, 1024px |

---

## 2. Page-by-Page Breakdown

### 2.1 Landing Page (`index.php`)

**Page Goal:**
- Marketing entry point for unauthenticated users
- Showcase features and drive signups
- Authenticate users via modal

#### Key UI Components

| Component | Purpose | User Interaction | Functional Behavior |
|-----------|---------|------------------|---------------------|
| `nav` | Top navigation bar | Scroll to hide/show | Hides on scroll down via `nav--hidden` class |
| `theme-toggle` | Switch dark/light mode | Click button | Calls `Theme.toggle()`, updates `data-theme` |
| `auth modal` | Sign in / sign up forms | Click trigger buttons | Opens overlay, switches tabs, validates forms |
| `hero section` | Value proposition | View/click CTA | Typing animation cycles through words |
| `page-loader` | Initial load state | None (auto) | Shows rabbit animation, auto-dismisses |

#### Feature Dependencies
- Auth modal triggers → Redirects to `dashboard/` on success
- Theme state → Persists in `localStorage`, syncs globally

---

### 2.2 Dashboard Shell (`dashboard/index.php`)

**Page Goal:**
- Container for all dashboard pages via iframe
- Provide persistent navigation and user context
- Manage page state and theme sync

#### Key UI Components

| Component | Purpose | User Interaction | Functional Behavior |
|-----------|---------|------------------|---------------------|
| `taskbar` | Main navigation bar | Click nav links | Updates iframe `src`, saves to `sessionStorage` |
| `taskbar__nav` | Page navigation links | Click link | Triggers `IframeManager.navigate(page)` |
| `taskbar__stats` | Display notification/app counts | View only | Static display (TODO: live updates) |
| `user-menu` | Account dropdown | Click trigger | Toggles dropdown, handles settings/logout |
| `theme-toggle` | Theme switcher | Click | Syncs theme to iframe via `syncTheme()` |
| `mobile-menu` | Mobile navigation drawer | Click hamburger | Opens full-screen menu overlay |
| `dashboard__iframe` | Content container | None (auto) | Loads child pages, shows loader on navigate |

#### Feature Dependencies
- `sessionStorage['rabbit_dashboard_page']` → Restores last page on refresh
- Theme sync → Parent broadcasts theme to iframe on load/change

---

### 2.3 Home Page (`dashboard/home.php`)

**Page Goal:**
- Dashboard overview and quick navigation
- Show activity summary and recent notifications
- Provide quick actions to key areas

#### Key UI Components

| Component | Purpose | User Interaction | Functional Behavior |
|-----------|---------|------------------|---------------------|
| `home__greeting` | Personalized welcome | View only | Displays user name from localStorage |
| `stats-row` | Key metrics display | View only | Shows total notifications, apps, today count |
| `quick-nav` | Navigation cards | Click card | Triggers parent `IframeManager.navigate()` |
| `panel (activity)` | Recent notifications | Click items | Shows last 5 notifications with timestamps |
| `panel (apps)` | Connected apps summary | View only | Lists top apps by notification count |

#### Feature Dependencies
- Quick nav cards → Communicate with parent via `window.parent`
- Stats → Read from localStorage or mock data

---

### 2.4 Notifications Page (`dashboard/notifications.php`)

**Page Goal:**
- List all notifications with filtering/search
- Enable bulk selection and deletion
- Provide date range and app filtering

#### Key UI Components

| Component | Purpose | User Interaction | Functional Behavior |
|-----------|---------|------------------|---------------------|
| `search-box` | Filter by text | Type query | Filters notifications in real-time |
| `date-filter` | Filter by date range | Click preset/custom | Filters by today/week/month/custom range |
| `app-filter` | Filter by source app | Select from dropdown | Shows only selected app's notifications |
| `sort-dropdown` | Change sort order | Select option | Sorts by newest/oldest/app/unread |
| `selectModeToggle` | Enter selection mode | Click button | Shows checkboxes on all notification items |
| `selection-bar` | Bulk actions toolbar | Click actions | Mark read/delete selected; shows count |
| `notification-item` | Individual notification | Click to expand | Shows full message; checkbox in select mode |
| `empty-state` | No results display | View only | Shown when filters return zero results |

#### Feature Dependencies
- App filter list → Populated from apps stored in localStorage
- Delete action → Removes from localStorage, updates counts
- Theme → Synced from parent on load

---

### 2.5 Apps Page (`dashboard/apps.php`)

**Page Goal:**
- Manage connected applications
- Add, edit, and delete apps
- View per-app statistics

#### Key UI Components

| Component | Purpose | User Interaction | Functional Behavior |
|-----------|---------|------------------|---------------------|
| `search-box` | Filter apps by name | Type query | Real-time filter of app grid |
| `sort-dropdown` | Change display order | Select option | Sort by notifications/recent/alpha/oldest |
| `addAppBtn` | Create new app | Click button | Opens add modal with empty form |
| `apps-grid` | Grid of app cards | View/click | Displays all apps as cards |
| `app-card` | Individual app | Click menu | Shows edit/delete options |
| `app-card__menu` | Context actions | Click trigger | Dropdown with Edit/Delete options |
| `appModal` | Add/edit form | Fill and submit | Validates, saves to localStorage |
| `color-picker` | App color selection | Click swatch | Updates icon preview in real-time |
| `confirmModal` | Delete confirmation | Type to confirm | Requires typing "DELETE" to enable button |

#### Feature Dependencies
- Apps list → Syncs to notification filter dropdown
- App deletion → Cascades to delete app's notifications
- Webhook URL → Auto-generated from app slug

---

### 2.6 Connect Page (`dashboard/connect.php`)

**Page Goal:**
- Provide API integration documentation
- Show code examples in multiple languages
- Allow API key management

#### Key UI Components

| Component | Purpose | User Interaction | Functional Behavior |
|-----------|---------|------------------|---------------------|
| `api-key-box` | Display API key | Click copy | Copies key to clipboard |
| `regenerateKey` | Reset API key | Click button | Generates new key (confirmation required) |
| `code-tabs` | Language selector | Click tab | Shows corresponding code block |
| `code-block` | Code example | View/copy | Syntax highlighted; copy button |
| `btn-copy` | Copy code | Click | Copies block content to clipboard |
| `guide-link` | Navigate to Apps | Click | Triggers parent navigation to apps.php |

#### Feature Dependencies
- App link in Step 2 → Navigates to apps.php via parent
- API key → Stored in localStorage

---

### 2.7 Settings Page (`dashboard/settings.php`)

**Page Goal:**
- Manage user profile information
- Provide destructive account actions
- Require confirmation for danger zone

#### Key UI Components

| Component | Purpose | User Interaction | Functional Behavior |
|-----------|---------|------------------|---------------------|
| `profileForm` | Update user details | Fill and submit | Saves name/email to localStorage |
| `fullName` input | User's display name | Edit text | Updates name in user menu on save |
| `email` input | User's email | Edit text | Validates email format |
| `resetDataBtn` | Clear all data | Click | Opens modal, requires "RESET" confirmation |
| `deleteAccountBtn` | Delete account | Click | Opens modal, requires "DELETE" confirmation |
| `confirmModal` | Confirmation dialog | Type phrase + click | Validates input matches required phrase |

#### Feature Dependencies
- Profile save → Updates localStorage, reflected in dashboard shell
- Reset data → Clears notifications/apps, keeps profile
- Delete account → Clears all localStorage, redirects to landing

---

## 3. Feature Sync Map

| Feature | Source Page | Affected Pages | Sync Mechanism |
|---------|-------------|----------------|----------------|
| Theme toggle | Any | All | `localStorage['rabbit_theme']` + event dispatch |
| Current page | Dashboard shell | Shell only | `sessionStorage['rabbit_dashboard_page']` |
| User profile | Settings | Dashboard shell, Home | `localStorage['rabbit_user_*']` |
| Apps list | Apps | Notifications (filter) | `localStorage['rabbit_apps']` |
| Notification count | Notifications | Dashboard shell, Home | `localStorage['rabbit_notifications']` |
| API key | Connect | Connect only | `localStorage['rabbit_api_key']` |

---

## 4. UI Behavior Notes

### State Classes
- `is-active` — Selected tab, current nav item, open dropdown item
- `is-open` — Expanded dropdown, open modal, open mobile menu
- `is-loading` — Loading state for containers
- `is-selected` — Selected item in multi-select mode
- `is-visible` — Animated-in element

### Loading States
- Dashboard iframe shows `.dashboard__loader` during page transitions
- Apps/Notifications show loading spinner until data renders
- Buttons show "Loading..." text when submitting

### Empty States
- Notifications: "No notifications yet" with illustration
- Apps: "No apps found" with "Add Your First App" CTA
- Search results: "No results match your search"

### Error Handling
- Form validation shows inline error messages
- Invalid email format blocks form submission
- Failed copy-to-clipboard shows error toast

### Keyboard Navigation
- `Escape` closes any open modal or dropdown
- `Enter` submits focused form
- Tab navigation works through all interactive elements

### Mobile Behavior
- Taskbar nav collapses into hamburger menu < 768px
- App grid switches to single column < 640px
- Modal becomes full-screen on mobile
- Touch targets minimum 44x44px

---

## 5. TODO Roadmap

| # | TODO |
|---|------|
| 1 | Implement real API endpoints for notification CRUD operations |
| 2 | Add WebSocket connection for real-time notification updates |
| 3 | Build notification detail modal with full message view |
| 4 | Add "Mark as unread" action to notification context menu |
| 5 | Implement notification grouping by app with collapsible sections |
| 6 | Add pagination or infinite scroll for notification list |
| 7 | Create app statistics dashboard with charts (notifications over time) |
| 8 | Add app status indicator (active/inactive/error) |
| 9 | Implement API key scoping per app instead of global key |
| 10 | Add webhook URL validation and test ping functionality |
| 11 | Build notification sound/browser notification preferences |
| 12 | Add email notification digest settings (daily/weekly/off) |
| 13 | Implement search history with recent queries |
| 14 | Add export notifications to CSV/JSON feature |
| 15 | Create keyboard shortcut overlay (press `?` to show) |
| 16 | Build onboarding tour for first-time users |
| 17 | Add avatar upload or Gravatar integration for user profile |
| 18 | Implement password change functionality in settings |
| 19 | Add two-factor authentication setup UI |
| 20 | Create activity log page showing API usage history |
| 21 | Add rate limit display in connect page (requests remaining) |
| 22 | Build notification templates feature for common notification types |
| 23 | Implement notification actions (buttons in notification body) |
| 24 | Add bulk import apps from JSON/CSV |
| 25 | Create shareable notification links for collaboration |

---

*End of Document*
