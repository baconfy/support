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



### Runners

Simple static style actions:

```php
namespace App\Actions;

use App\Models\Post;
use App\Events\PostHasBeenCreated;
use Baconfy\Support\Concerns\Runner;

class CreatePostAction
{
    use Runner;

    public function handle(array $payload): Post
    {
        $post = Post::create($payload);

        event(new PostHasBeenCreated($post));

        return $post;
    }
}
```

```php
namespace App\Controllers;

use App\Actions\CreatePostAction;

class PostController
{
    public function store(Request $request)
    {
        CreatePostAction::run($request->all());

        return back();
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
