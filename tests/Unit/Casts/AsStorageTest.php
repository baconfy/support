<?php

use Baconfy\Support\Casts\AsStorage;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(fn() => Storage::fake('local'));

it('can cast a file path to storage url', function () {
    // Arrange
    $cast = new AsStorage();
    $model = new class extends Model {
        protected $table = 'test_table';
    };

    // Act
    $result = $cast->get($model, 'document', 'documents/test-file.pdf', []);

    // Assert
    expect($result)->toBe(Storage::url('documents/test-file.pdf'));
});

it('returns null when value is null', function () {
    // Arrange
    $cast = new AsStorage();
    $model = new class extends Model {
        protected $table = 'test_table';
    };

    // Act
    $result = $cast->get($model, 'document', null, []);

    // Assert
    expect($result)->toBeNull();
});

it('returns original value when setting', function () {
    // Arrange
    $cast = new AsStorage();
    $model = new class extends Model {
        protected $table = 'test_table';
    };

    $filePath = 'documents/test-file.pdf';

    // Act
    $result = $cast->set($model, 'document', $filePath, []);

    // Assert
    expect($result)->toBe($filePath);
});

it('treats an empty string as no file', function () {
    $cast = new AsStorage();
    $model = new class extends Model {
        protected $table = 'test_table';
    };

    // '' used to become "/storage/" — a URL to nothing.
    expect($cast->get($model, 'document', '', []))->toBeNull();
});

it('leaves a value that is already a url alone', function () {
    $cast = new AsStorage();
    $model = new class extends Model {
        protected $table = 'test_table';
    };

    // Storage::url() on an absolute url produced "/storage/https://…".
    expect($cast->get($model, 'document', 'https://cdn.example.com/a.jpg', []))->toBe('https://cdn.example.com/a.jpg');
});

it('stores an uploaded file under the attribute name and keeps the path', function () {
    $cast = new AsStorage();
    $model = new class extends Model {
        protected $table = 'test_table';
    };

    $path = $cast->set($model, 'avatar', UploadedFile::fake()->image('me.jpg'), []);

    // Assigning an upload used to throw a TypeError: the cast returned the
    // file object where a string was declared.
    expect($path)->toStartWith('avatar/');
    Storage::assertExists($path);
});

it('stores null as null', function () {
    $cast = new AsStorage();
    $model = new class extends Model {
        protected $table = 'test_table';
    };

    expect($cast->set($model, 'avatar', null, []))->toBeNull();
});

it('declares the cast contract', function () {
    expect(new AsStorage())->toBeInstanceOf(CastsAttributes::class);
});

it('works with different file types', function () {
    // Arrange
    $cast = new AsStorage();
    $model = new class extends Model {
        protected $table = 'test_table';
    };

    $testCases = [
        'images/photo.jpg',
        'documents/report.pdf',
        'videos/presentation.mp4',
        'audio/music.mp3',
        'files/data.csv'
    ];

    foreach ($testCases as $filePath) {
        // Act
        $result = $cast->get($model, 'file', $filePath, []);

        // Assert
        expect($result)->toBe(Storage::url($filePath));
    }
});

it('preserves file paths with subdirectories', function () {
    // Arrange
    $cast = new AsStorage();
    $model = new class extends Model {
        protected $table = 'test_table';
    };

    $filePath = 'uploads/2024/january/user-documents/important-file.pdf';

    // Act
    $result = $cast->get($model, 'document', $filePath, []);

    // Assert
    expect($result)->toBe(Storage::url($filePath));
});

it('can be used in a real model', function () {
    // Arrange
    $model = new class extends Model {
        protected $table = 'test_models';
        protected $fillable = ['name', 'document'];

        protected $casts = [
            'document' => AsStorage::class
        ];
    };

    // Act
    $model->document = 'documents/test.pdf';

    // Assert
    expect($model->document)->toBe(Storage::url('documents/test.pdf'));
});