<?php

declare(strict_types=1);

namespace Baconfy\Support\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * A column that holds a path on the default disk.
 *
 * Reads as the public url of that path. Writes accept an upload, which is
 * stored under the attribute's name, or a path already on the disk.
 *
 * @implements CastsAttributes<string|null, string|null>
 */
final class AsStorage implements CastsAttributes
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Already a url — an external asset, or a row written before this
        // cast existed. Storage::url() would prefix it into nonsense.
        if (Str::startsWith($value, ['http://', 'https://', '//'])) {
            return $value;
        }

        return Storage::url($value);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof UploadedFile) {
            $path = $value->store($key);

            if ($path === false) {
                throw new RuntimeException("Could not store the upload for [{$key}].");
            }

            return $path;
        }

        return $value;
    }
}
