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
