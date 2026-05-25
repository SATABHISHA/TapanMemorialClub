<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageHelper
{
    public static function compressImage(UploadedFile|string $source, int $quality = 82, int $maxBytes = 5242880): string
    {
        $manager = new ImageManager(new Driver());
        $image = is_string($source) ? $manager->read($source) : $manager->read($source->getRealPath());
        $image->scaleDown(width: 2200, height: 2200);

        $binary = (string) $image->toJpeg($quality);
        $minQuality = 42;
        $maxAttempts = 8;
        $attempt = 0;

        while (strlen($binary) > $maxBytes && $attempt < $maxAttempts) {
            $attempt++;
            $quality = max($minQuality, $quality - 7);

            $working = $manager->read($binary);
            $dimensions = self::getDimensions($binary);
            $width = (int) ($dimensions['width'] ?? 0);
            $height = (int) ($dimensions['height'] ?? 0);

            if ($width > 1280 || $height > 1280) {
                $working->scaleDown(
                    width: max(1280, (int) round($width * 0.88)),
                    height: max(1280, (int) round($height * 0.88))
                );
            }

            $binary = (string) $working->toJpeg($quality);
        }

        return $binary;
    }

    public static function imageToBytes(string $binary): string
    {
        return $binary;
    }

    public static function bytesToImage(?string $bytes): ?string
    {
        if ($bytes === null) {
            return null;
        }

        return base64_encode($bytes);
    }

    public static function generateThumbnail(string $binary, int $width = 480, int $height = 320): string
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($binary)->cover($width, $height);

        return (string) $image->toJpeg(70);
    }

    public static function toWebpBytes(string $binary, int $quality = 75): string
    {
        $manager = new ImageManager(new Driver());

        return (string) $manager->read($binary)->toWebp($quality);
    }

    public static function getDimensions(string $binary): array
    {
        $info = @getimagesizefromstring($binary);

        return [
            'width' => $info[0] ?? null,
            'height' => $info[1] ?? null,
        ];
    }
}
