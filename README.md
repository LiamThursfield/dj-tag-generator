# DJ Tag Generator

A Laravel application for generating professional DJ tags - the audio clips that DJs and producers play at the start of their tracks or during their DJ sets to identify themselves.

## Features

### Core Functionality
- **Text-to-Speech Generation**: Convert any phrase, word, or DJ name into a professional audio tag
- **Voice Customization**: Multiple voice types and styles to choose from
- **Sound Effects**: Add professional effects to make your tag stand out
- **Audio Export**: Download your generated tags in various formats

### Planned Features
- Multiple voice options (male, female, robotic, etc.)
- Sound effects library (reverb, echo, pitch shift, etc.)
- Audio preview before download
- Tag history and favorites
- Batch generation for multiple tags
- Custom audio processing options

## Technology Stack

### Backend
- **PHP**: 8.2+
- **Laravel**: 12.x
- **Database**: SQLite (development) / MySQL/PostgreSQL (production)
- **Queue System**: Laravel Queues for async audio processing

### Frontend
- **Vue.js**: 3.5+
- **Inertia.js**: 2.x (for seamless SPA experience)
- **TailwindCSS**: 4.x (for styling)
- **TypeScript**: For type-safe frontend code

### Development Tools
- **Laravel Sail**: Docker-based development environment
- **Laravel Boost**: MCP server for enhanced development
- **Laravel Wayfinder**: Type-safe routing
- **Laravel Fortify**: Authentication backend
- **Pest**: Testing framework
- **Laravel Pint**: Code formatting

## Getting Started

### Prerequisites
- Docker Desktop installed and running
- Git

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd dj-tag-generator
   ```

2. **Copy environment file**
   ```bash
   cp .env.example .env
   ```

3. **Start Laravel Sail**
   ```bash
   vendor/bin/sail up -d
   ```

4. **Install dependencies**
   ```bash
   vendor/bin/sail composer install
   vendor/bin/sail npm install
   ```

5. **Generate application key**
   ```bash
   vendor/bin/sail artisan key:generate
   ```

6. **Run migrations**
   ```bash
   vendor/bin/sail artisan migrate
   ```

7. **Build frontend assets**
   ```bash
   vendor/bin/sail npm run build
   ```

8. **Open the application**
   ```bash
   vendor/bin/sail open
   ```

### Development

Start the development server with hot module replacement:

```bash
vendor/bin/sail composer run dev
```

This will start:
- Laravel development server
- Queue worker
- Log viewer (Pail)
- Vite dev server with HMR

### Testing

Run the test suite:

```bash
vendor/bin/sail artisan test
```

Run specific tests:

```bash
vendor/bin/sail artisan test --filter=TagGenerationTest
```

### Code Quality

Format code with Laravel Pint:

```bash
vendor/bin/sail bin pint
```

Run ESLint:

```bash
vendor/bin/sail npm run lint
```

Format frontend code:

```bash
vendor/bin/sail npm run format
```

## Project Structure

```
app/
├── Actions/          # Single-purpose action classes
├── Http/
│   ├── Controllers/  # HTTP controllers
│   └── Requests/     # Form request validation
├── Models/           # Eloquent models
└── Providers/        # Service providers

resources/
├── js/
│   ├── Components/   # Vue components
│   ├── Layouts/      # Page layouts
│   ├── Pages/        # Inertia pages
│   └── types/        # TypeScript type definitions
└── css/              # Stylesheets

tests/
├── Feature/          # Feature tests
└── Unit/             # Unit tests
```

## Environment Variables

Key environment variables to configure:

```env
APP_NAME="DJ Tag Generator"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Audio Processing (to be configured)
# TTS_SERVICE=
# TTS_API_KEY=
```

## Contributing

1. Follow Laravel and Vue.js best practices
2. Write tests for new features
3. Run `vendor/bin/sail bin pint` before committing
4. Ensure all tests pass before submitting PRs

## License

MIT License

## Support

For issues and questions, please open an issue on the repository.
