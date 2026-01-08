# Contributing to Rabbit

First off, thank you for considering contributing to Rabbit! It's people like you that make Rabbit such a great tool.

## Code of Conduct

By participating in this project, you are expected to uphold our Code of Conduct. Please report unacceptable behavior to [hello@rabbit.dev](mailto:hello@rabbit.dev).

## How Can I Contribute?

### 🐛 Reporting Bugs

Before creating bug reports, please check the existing issues to avoid duplicates. When you create a bug report, include as many details as possible:

- **Use a clear and descriptive title**
- **Describe the exact steps to reproduce the problem**
- **Provide specific examples** (code snippets, screenshots)
- **Describe the behavior you observed and what you expected**
- **Include your environment details** (OS, PHP version, browser)

### 💡 Suggesting Features

Feature suggestions are tracked as GitHub issues. When creating a feature request:

- **Use a clear and descriptive title**
- **Provide a detailed description** of the suggested feature
- **Explain why this feature would be useful**
- **List any alternatives you've considered**

### 🔧 Pull Requests

1. **Fork the repo** and create your branch from `main`
2. **Follow the coding style** used throughout the project
3. **Write clear commit messages**
4. **Test your changes** thoroughly
5. **Update documentation** if needed
6. **Create a Pull Request** with a clear description

## Development Setup

### Prerequisites

- PHP 8.0+
- Node.js 18+ (for development tools)
- MySQL 5.7+ (optional)

### Getting Started

```bash
# Clone your fork
git clone https://github.com/your-username/rabbit.git
cd rabbit

# Set up local environment
cp .env.example .env

# Start development server
php -S localhost:8000
```

## Style Guide

### PHP

- Follow PSR-12 coding standards
- Use meaningful variable and function names
- Add PHPDoc comments for functions and classes
- Keep functions focused and small

### JavaScript

- Use ES6+ features
- Follow the existing code patterns
- Use meaningful variable names
- Add JSDoc comments for functions

### CSS

- Use CSS custom properties for theming
- Follow BEM naming convention
- Keep selectors specific but not overly nested
- Group related properties together

### Commit Messages

Use clear and descriptive commit messages:

```
feat: add notification templates support
fix: resolve date filter timezone issue
docs: update API reference
style: format code according to style guide
refactor: simplify notification filtering logic
test: add unit tests for API endpoints
```

## Project Structure

```
rabbit/
├── API/           # Backend API endpoints
├── CSS/           # Global styles
├── JS/            # Global scripts
├── dashboard/     # Dashboard application
├── database/      # Database migrations
└── docs/          # Documentation
```

## Questions?

Feel free to open an issue with your question or reach out to the maintainers.

---

Thank you for contributing! 🐰
