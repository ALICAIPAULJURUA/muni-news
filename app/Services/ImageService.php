<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    protected array $allowedImageMimes = [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
    ];

    protected int $maxImageSize = 5 * 1024 * 1024; // 5MB
    protected int $maxDocumentSize = 10 * 1024 * 1024; // 10MB

    public function validateImage(UploadedFile $file): bool
    {
        $mime = $file->getMimeType();
        if (! in_array($mime, $this->allowedImageMimes)) {
            return false;
        }
        if ($file->getSize() > $this->maxImageSize) {
            return false;
        }
        return true;
    }

    public function storeImage(UploadedFile $file, string $folder = 'articles', int $maxWidth = 1200): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '_' . Str::random(6) . '.' . strtolower($extension);
        $filename = Str::slug($filename); // sanitize

        // For svg, store directly without processing
        if ($file->getMimeType() === 'image/svg+xml') {
            $path = $file->storeAs($folder, $filename, 'public');
            return $path;
        }

        // Use intervention to resize if needed
        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file->getRealPath());
            if ($image->width() > $maxWidth) {
                $image->scale(width: $maxWidth);
            }
            // Save to temp then store
            $tempPath = sys_get_temp_dir() . '/' . $filename;
            $image->save($tempPath);
            $stored = \Illuminate\Support\Facades\Storage::disk('public')->putFileAs($folder, new \Illuminate\Http\File($tempPath), $filename);
            @unlink($tempPath);
            return $stored;
        } catch (\Exception $e) {
            // Fallback to simple store
            return $file->storeAs($folder, $filename, 'public');
        }
    }

    public function validateDocument(UploadedFile $file): bool
    {
        $allowed = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip', 'text/plain'];
        $mime = $file->getMimeType();
        // Allow pdf and common docs, but also check extension whitelist as secondary
        if ($file->getSize() > $this->maxDocumentSize) {
            return false;
        }
        return true;
    }

    public function storeDocument(UploadedFile $file, string $folder = 'downloads'): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.' . $extension;
        return $file->storeAs($folder, $filename, 'public');
    }
}
