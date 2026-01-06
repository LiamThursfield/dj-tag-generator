# Contributing to DJ Tag Generator

Thank you for your interest in contributing to the DJ Tag Generator! This guide will help you get started.

## Development Setup

### Prerequisites
- Docker Desktop installed and running
- Git
- Basic knowledge of Laravel, Vue.js, and TypeScript

### Getting Started

1. **Fork and clone the repository**
   ```bash
   git clone https://github.com/yourusername/dj-tag-generator.git
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

7. **Start development server**
   ```bash
   vendor/bin/sail composer run dev
   ```

## Development Workflow

### Making Changes

1. **Create a feature branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Make your changes**
   - Follow existing code conventions
   - Write tests for new features
   - Update documentation as needed

3. **Run code quality checks**
   ```bash
   # Format PHP code
   vendor/bin/sail bin pint
   
   # Format JavaScript/Vue code
   vendor/bin/sail npm run format
   
   # Lint frontend code
   vendor/bin/sail npm run lint
   ```

4. **Run tests**
   ```bash
   # Run all tests
   vendor/bin/sail artisan test
   
   # Run specific test file
   vendor/bin/sail artisan test tests/Feature/TagTest.php
   
   # Run with filter
   vendor/bin/sail artisan test --filter=tag_creation
   ```

5. **Commit your changes**
   ```bash
   git add .
   git commit -m "feat: add voice selection feature"
   ```

### Commit Message Convention

We follow conventional commits:

- `feat:` - New feature
- `fix:` - Bug fix
- `docs:` - Documentation changes
- `style:` - Code style changes (formatting, etc.)
- `refactor:` - Code refactoring
- `test:` - Adding or updating tests
- `chore:` - Maintenance tasks

Examples:
```
feat: add ElevenLabs TTS integration
fix: resolve audio processing timeout issue
docs: update README with deployment instructions
test: add tests for tag generation flow
```

## Code Standards

### PHP/Laravel

- **Follow PSR-12**: Use Laravel Pint to format code
- **Type hints**: Always use return type declarations and parameter types
- **Form Requests**: Use dedicated Form Request classes for validation
- **Action Classes**: Extract complex business logic into Action classes
- **Eloquent**: Use relationships and eager loading to prevent N+1 queries
- **Config**: Never use `env()` outside config files
- **Named Routes**: Always use named routes with `route()` helper

Example:
```php
// Good
public function store(StoreTagRequest $request): RedirectResponse
{
    $tag = (new GenerateTag)($request->validated());
    
    return redirect()->route('tags.show', $tag);
}

// Bad
public function store(Request $request)
{
    $request->validate([...]);
    // inline logic
}
```

### Vue/TypeScript

- **Composition API**: Use script setup syntax
- **Type Safety**: Define proper TypeScript types
- **Wayfinder**: Use for type-safe routing
- **Components**: Keep components focused and reusable
- **Props/Emits**: Define with TypeScript interfaces

Example:
```vue
<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/TagController'

interface Props {
  tag: Tag
}

const props = defineProps<Props>()
</script>
```

### Testing

- **Use Pest**: All tests should use Pest syntax
- **Descriptive**: Use clear test descriptions
- **Factories**: Use model factories for test data
- **Coverage**: Test happy paths, error cases, and edge cases

Example:
```php
it('generates a tag with custom voice', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)
        ->post(route('tags.store'), [
            'name' => 'DJ Test',
            'voice_type' => 'alloy',
        ]);
    
    $response->assertRedirect();
    expect(Tag::count())->toBe(1);
});
```

## Project Structure

### Backend
```
app/
├── Actions/          # Business logic classes
├── Http/
│   ├── Controllers/  # Thin controllers
│   └── Requests/     # Form validation
├── Models/           # Eloquent models
└── Services/         # External service integrations
```

### Frontend
```
resources/js/
├── Components/
│   ├── UI/          # Reusable UI components
│   └── Tag/         # Domain-specific components
├── Pages/           # Inertia pages
└── types/           # TypeScript definitions
```

### Tests
```
tests/
├── Feature/         # Feature tests (most tests go here)
└── Unit/            # Unit tests
```

## Pull Request Process

1. **Update documentation** if needed
2. **Add/update tests** for your changes
3. **Run all quality checks**:
   ```bash
   vendor/bin/sail bin pint
   vendor/bin/sail npm run format
   vendor/bin/sail artisan test
   ```
4. **Create pull request** with clear description
5. **Link related issues** if applicable
6. **Wait for review** and address feedback

### PR Checklist

- [ ] Code follows project conventions
- [ ] Tests added/updated and passing
- [ ] Code formatted with Pint and Prettier
- [ ] Documentation updated if needed
- [ ] No console errors or warnings
- [ ] Commit messages follow convention

## Areas for Contribution

### High Priority
- TTS service integrations (OpenAI, Google, ElevenLabs)
- Audio effects processing with FFmpeg
- User interface improvements
- Test coverage

### Medium Priority
- Tag preset system
- Social sharing features
- Performance optimizations
- Documentation improvements

### Good First Issues
- UI component refinements
- Additional voice options
- Error message improvements
- Test additions

## Getting Help

- **Documentation**: Check PROJECT.md for technical details
- **Issues**: Search existing issues before creating new ones
- **Discussions**: Use GitHub Discussions for questions
- **Code**: Review existing code for patterns and conventions

## Code Review Guidelines

When reviewing PRs:

- **Be constructive**: Suggest improvements, don't just criticize
- **Be specific**: Point to exact lines and explain why
- **Be timely**: Review within a few days if possible
- **Test locally**: Pull the branch and test the changes

## License

By contributing, you agree that your contributions will be licensed under the MIT License.

## Questions?

Feel free to open an issue or start a discussion if you have questions about contributing!
