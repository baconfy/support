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

### UUIDs

Add automatic UUIDs to your models:

```php
use Baconfy\Support\Traits\HasUuid;

class User extends Model
{
    use HasUuid;

    protected function getUuidColumn(): string
    {
        return 'uuid';
    }
}
```

### Slugs

Generate unique slugs from an attribute:

```php
use Baconfy\Support\Traits\HasSlug;

class Post extends Model
{
    use HasSlug;

    protected function getSlugColumn(): string
    {
        return 'slug';
    }

    protected function getAttributeToBeSlugified(): string
    {
        return 'title';
    }
}
```

> ✨ If the slug already exists, a suffix is added automatically to keep it unique — even with soft deletes!

## 📄 License

MIT — do what you want, just give credit.  
Made with ❤️ by [Baconfy](https://github.com/baconfy)
