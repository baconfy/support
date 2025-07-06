# 🍳 Baconfy Support

A handy Laravel support package offering reusable traits for common model behavior like UUIDs and slugs — perfect for keeping your code clean and consistent.

## 🚀 Features

- ✅ Auto UUID generation for Eloquent models
- 🔗 Unique slugs (with optional soft delete awareness)
- 🧩 Drop-in traits — quick to use, easy to maintain
- 🔄 Minimal setup, maximum utility

## 📦 Installation

```bash
composer require baconfy/support
```

## ⚙️ Usage

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

## 📄 License

MIT — do what you want, just give credit.  
Made with ❤️ by [Baconfy](https://github.com/baconfy)
