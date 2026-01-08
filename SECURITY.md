# Rabbit Security Policy

## Supported Versions

| Version | Supported          |
| ------- | ------------------ |
| 1.0.x   | :white_check_mark: |
| < 1.0   | :x:                |

## Reporting a Vulnerability

We take security seriously at Rabbit. If you discover a security vulnerability, please follow these steps:

### 1. Do NOT Create a Public Issue

Security vulnerabilities should not be reported through public GitHub issues.

### 2. Email Us Directly

Send a detailed report to: **security@rabbit.dev**

Include:
- Description of the vulnerability
- Steps to reproduce
- Potential impact
- Any suggested fixes (optional)

### 3. What to Expect

- **Acknowledgment:** Within 48 hours
- **Initial Assessment:** Within 1 week
- **Resolution Timeline:** Depends on severity
  - Critical: 24-72 hours
  - High: 1-2 weeks
  - Medium: 2-4 weeks
  - Low: Next release cycle

### 4. Disclosure Policy

- We follow responsible disclosure practices
- We will credit you in our security advisories (unless you prefer to remain anonymous)
- We ask that you do not disclose the vulnerability publicly until we have released a fix

## Security Best Practices

When deploying Rabbit, please ensure:

### Server Configuration
- Keep PHP updated to the latest stable version
- Use HTTPS in production
- Configure proper file permissions
- Disable directory listing

### Application Security
- Use strong, unique API keys
- Regularly rotate API keys
- Monitor for unusual activity
- Keep the application updated

### Database Security
- Use strong database passwords
- Limit database user permissions
- Regular backups
- Encrypt sensitive data at rest

## Security Features

Rabbit includes the following security measures:

- **API Key Hashing:** Keys are hashed before storage
- **Rate Limiting:** Prevents brute-force attacks
- **Input Validation:** All inputs are sanitized
- **XSS Protection:** Output encoding enabled
- **CSRF Protection:** Token-based protection on forms
- **SQL Injection Prevention:** Prepared statements used

## Contact

For security concerns: security@rabbit.dev

For general inquiries: hello@rabbit.dev

---

Thank you for helping keep Rabbit secure! 🔒
