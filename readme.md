# WP-CLI Models by VigihDev

[![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-blue.svg)](https://php.net)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![WP-CLI](https://img.shields.io/badge/WP--CLI-2.5%2B-green.svg)](https://wp-cli.org)

A structured, type-safe WP-CLI package for WordPress development using modern PHP patterns, DTOs, and clean architecture principles.

## ✨ Features

- **🚀 Modern PHP 8.1+** - Enums, readonly properties, constructor promotion
- **📦 Type Safety** - Strict types, DTOs, and interfaces
- **🔧 WP-CLI Commands** - Ready-to-use commands for WordPress development
- **🏗️ Clean Architecture** - Separation of concerns, SOLID principles
- **✅ Validation** - Comprehensive validation with detailed error messages
- **📝 Code Generation** - Scaffold WordPress components programmatically
- **🎯 Enum Support** - Type-safe enums for WordPress constants

## 📁 Project Structure

```
src/
├── Commands/          # WP-CLI command handlers
├── Contracts/         # Interfaces and abstractions
├── DTOs/             # Data Transfer Objects
├── Entities/         # Business entities
├── Enums/            # Type-safe enumerations
├── Exceptions/       # Custom exceptions
├── Factories/        # Object factories
├── Helpers/          # Utility functions
└── Services/         # Business logic services
```

## 🚀 Quick Start

### Installation

```bash
# Install via Composer
composer require vigihdev/wp-cli-models

# Or install globally with WP-CLI
wp package install git@github.com:vigihdev/wp-cli-models.git
```

## 📖 Usage Examples

### Creating a Post with DTO

```php
use Vigihdev\WpCliModels\DTOs\Args\PostArgsDto;
use Vigihdev\WpCliModels\Enums\PostStatus;

$postArgs = new PostArgsDto(
    title: "My First Post",
    content: "This is the post content",
    status: PostStatus::PUBLISH,
    author: 1
);

// Validate before use
$postArgs->validate();

// Create post using WordPress functions
$postId = wp_insert_post($postArgs->toArray());
```

### Custom Command Example

```php
namespace Vigihdev\WpCliModels\Commands;

use WP_CLI;
use Vigihdev\WpCliModels\DTOs\Args\MenuArgsDto;
use Vigihdev\WpCliModels\Enums\MenuLocation;

class CreateMenuCommand
{
    public function __invoke($args, $assoc_args)
    {
        $menuArgs = MenuArgsDto::fromArray([
            'name' => $args[0],
            'location' => $assoc_args['location'] ?? 'primary'
        ]);

        $menuArgs->validate();

        // Your menu creation logic here
        WP_CLI::success("Menu created successfully!");
    }
}
```

## 🏗️ Architecture Overview

### DTOs (Data Transfer Objects)

```php
// Immutable data containers
$dto = new PostArgsDto(
    title: "Title",
    status: PostStatus::DRAFT
);

// From array
$dto = PostArgsDto::fromArray($data);

// To array for WordPress functions
$wpData = $dto->toArray();
```

### Enums (Type-safe Constants)

```php
use Vigihdev\WpCliModels\Enums\MenuLocation;

// Type safety
$location = MenuLocation::PRIMARY;

// Get human-readable labels
echo $location->label(); // "Primary Navigation"

// Validation
if (!in_array($input, MenuLocation::values())) {
    throw new \InvalidArgumentException('Invalid location');
}
```

### Validation

```php
use Vigihdev\WpCliModels\Exceptions\ValidationException;

try {
    $dto->validate();
} catch (ValidationException $e) {
    // Get detailed errors
    foreach ($e->getErrors() as $field => $error) {
        echo "$field: $error\n";
    }
}
```

## 🔧 Development

### Requirements

- PHP 8.1 or higher
- WordPress 5.9 or higher
- WP-CLI 2.5 or higher
- Composer

### Setup Development Environment

```bash
# Clone repository
git clone https://github.com/vigihdev/wp-cli-models.git
cd wp-cli-models

# Install dependencies
composer install

# Run tests
composer test

# Check code quality
composer check

# Fix code style
composer format
```

### Adding New Commands

1. Create DTOs in `src/DTOs/Args/`
2. Create Enums in `src/Enums/`
3. Create Command in `src/Commands/`
4. Register in `composer.json`

## 📚 Documentation

- [API Documentation](docs/api.md) - Complete API reference
- [Examples](examples/) - Practical usage examples
- [Contributing](CONTRIBUTING.md) - How to contribute
- [Changelog](CHANGELOG.md) - Version history

## 🤝 Contributing

Contributions are welcome! Please read our [Contributing Guide](CONTRIBUTING.md) for details.

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add/update tests
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- WordPress Core Team
- WP-CLI Maintainers
- PHP Community
- All contributors and users

## 📞 Support

- [GitHub Issues](https://github.com/vigihdev/wp-cli-models/issues)
- [Discussions](https://github.com/vigihdev/wp-cli-models/discussions)
- Email: vigihdev@gmail.com

---

Built with ❤️ by [Vigih Dev](https://github.com/vigihdev)

## Questions?

Open an issue or start a discussion on GitHub.
