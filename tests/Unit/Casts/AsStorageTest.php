<?php

use Baconfy\Support\Casts\AsStorage;
use Illuminate\Database\Eloquent\Model;
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

it('handles empty string values', function () {
    // Arrange
    $cast = new AsStorage();
    $model = new class extends Model {
        protected $table = 'test_table';
    };

    // Act
    $result = $cast->get($model, 'document', '', []);

    // Assert
    expect($result)->toBe(Storage::url(''));
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