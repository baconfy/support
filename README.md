# Baconfy Support

A handy Laravel support package: reusable traits for common model behavior — UUIDs, ULIDs, slugs — a storage cast, a FormRequest with per-verb rules, and a base for application intents that validate and authorize before they act.

## Features

- Auto UUID and ULID generation for Eloquent models
- Unique slugs (with optional soft delete awareness)
- Intents: one class per thing the app can be asked to do, callable from any entry point
- Drop-in traits — quick to use, easy to maintain
- Minimal setup, maximum utility

## Installation

```bash
composer require baconfy/support
```

## Usage

### Casts

Storage cast for eloquent models

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Baconfy\Support\Casts\AsStorage;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

final class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'avatar' => AsStorage::class,
        ];
    }
}

```

### FormRequest

A better way to validate your forms. Available methods: `authorize`, `view`, `store`, `update`, `destroy`.

```php
<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Baconfy\Support\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

final class ProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the post request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function store(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
        ];
    }
    
    /**
     * Get the validation rules that apply to the put/patch request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function update(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
        ];
    }
}
```

### UUIDs

Add automatic UUIDs to your models:

```php
use Baconfy\Support\Concerns\Uuid;

class User extends Model
{
    use Uuid;
}
```

### ULIDs

Same idea, sortable by time. Fills a `ulid` column on create:

```php
use Baconfy\Support\Concerns\Ulid;

class Order extends Model
{
    use Ulid;
}
```

> Both traits fill a **secondary** column next to your usual `id`. For a ULID or UUID primary key, use Laravel's own `HasUlids` / `HasUuids`.

### Slugs

Generate unique slugs from an attribute:

```php
use Baconfy\Support\Concerns\Slugfy;

class Post extends Model
{
    use Slugfy;
}
```

> ✨ If the slug already exists, a suffix is added automatically to keep it unique — even with soft deletes!

### Intents

One class per thing the application can be asked to do — register a user, close a month — callable the same way from a controller, a console command or an MCP tool. It runs the FormRequest lifecycle without the HTTP: `sanitize` → `authorize` → `rules` → `after` → `handle`. Only `__invoke` is public, so nothing reaches `handle()` without passing the checks.

```php
use Baconfy\Support\Intent;

/** @extends Intent<User> */
final class RegisterUser extends Intent
{
    public function __construct(private readonly CreateUser $createUser) {}

    protected function sanitize(array $data): array
    {
        return [...$data, 'email' => strtolower($data['email'] ?? '')];
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    protected function handle(array $data): User
    {
        return ($this->createUser)($data['name'], $data['email'], $data['password']);
    }
}

$user = $registerUser($request->all());
```

Every hook is optional but `handle()`. Failures throw — `ValidationException`, `AuthorizationException` — and the entry point decides how to answer.

> A field with no rule is **refused**, not dropped. Input may come from a console argument or a tool call as easily as from a form, and a key nobody declared is a key nobody checked. An intent with no rules accepts no input at all.

## License

MIT — do what you want, just give credit.  
Made with ❤️ by [Baconfy](https://github.com/baconfy)
