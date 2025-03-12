<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use function storage_path;

abstract class TestCase extends BaseTestCase
{
    use MocksFileUploads;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
    }

    public function uploadedTextFile(): UploadedFile
    {
        $baseFile = new File(storage_path('stubs/text.txt'));

        $path = Storage::putFile('/', $baseFile);

        return $this->uploadedFile(Storage::path($path));
    }
}
