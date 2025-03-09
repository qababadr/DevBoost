<?php

namespace BadrQaba\DevBoost\Media\Core;

use Exception;
use RuntimeException;
use FilesystemIterator;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use BadrQaba\DevBoost\Exception\CompressionError;
use BadrQaba\DevBoost\Exception\MediaNotFoundException;
use BadrQaba\DevBoost\Exception\UnreachableMediaException;
use BadrQaba\DevBoost\Exception\UnsupportedImageException;
use BadrQaba\DevBoost\Exception\InvalidCompressionQualityException;

/**
 * MediaToolkit
 * 
 * A powerful media handling utility that provides essential functionalities 
 * for downloading, validating, compressing, and managing media files. 
 * This trait is designed to streamline media-related operations such as:
 * 
 * - Downloading files using cURL or file streams
 * - Retrieving file metadata (extension, filename)
 * - Validating supported image formats
 * - Compressing images with adjustable quality
 * - Ensuring efficient memory usage during image processing
 * 
 * This toolkit is part of the `BadrQaba\DevBoost\Media\Core` package 
 * and includes robust exception handling for error-prone operations.
 * 
 * @package BadrQaba\DevBoost\Media\Core
 * @author  
 * @license MIT
 */
trait MediaToolkit
{
    /**
     * Download a a file to the output folder using curl
     * @param string $url The image url
     * @param string $outputFolder The output folder where the image should be downloaded
     * @param int $timeout The request timeout
     * @throws \BadrQaba\DevBoost\Exception\UnreachableMediaException
     * @throws \BadrQaba\DevBoost\Exception\UnsupportedImageException
     * @return string
     */
    public function curlDownload(
        string $url,
        string $outputFolder,
        int $timeout = 200
    ): string {
        try {
            if (!is_dir($outputFolder)) {
                mkdir($outputFolder, 0755, true);
            }

            $fileExtension = strtolower($this->getFileExtension($url));
            if ($fileExtension === null) {
                throw new UnsupportedImageException();
            }

            $fileName = md5($url) . '.' . $fileExtension;
            $filePath = "$outputFolder/$fileName";

            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => $timeout,
                CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36",
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER => [
                    "Referer: https://qababadr.com/",
                    "Accept: image/webp,image/apng,image/*,*/*;q=0.8"
                ]
            ]);

            $imageData = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200 || !$imageData) {
                throw new UnreachableMediaException("Failed to download image. HTTP Code: $httpCode");
            }

            file_put_contents($filePath, $imageData);
            return $filePath;
        } catch (Exception $exp) {
            throw $exp;
        }
    }

    /**
     * Download a a file to the output folder
     * @param string $url The image url
     * @param string $outputFolder The output folder where the image should be downloaded
     * @param int $timeout The request timeout
     * @throws \BadrQaba\DevBoost\Exception\UnreachableMediaException
     * @throws \BadrQaba\DevBoost\Exception\UnsupportedImageException
     * @return string
     */
    public function download(
        string $url,
        string $outputFolder,
        int $timeout = 200,
    ): string {
        try {
            $context = stream_context_create([
                "http" => [
                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36",
                    "timeout" => $timeout
                ],
            ]);

            if (!$stream = @fopen($url, 'r', false, $context)) {
                throw new UnreachableMediaException();
            }

            if (!is_dir($outputFolder)) {
                mkdir($outputFolder, 0755, true);
            }

            $fileExtension = strtolower($this->getFileExtension($url));

            if ($fileExtension == null) {
                throw new UnsupportedImageException();
            }

            $fileName = md5($url) . '.' . $fileExtension;
            $filePath = "$outputFolder/$fileName";

            file_put_contents($filePath, $stream);

            fclose($stream);

            unset($stream);
            unset($context);

            return $filePath;
        } catch (Exception $exp) {
            throw $exp;
        }
    }


    /**
     * Get the file extension
     * @param string $url
     * @return mixed|string|null
     */
    public function getFileExtension(string $url): ?string
    {
        $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH));
        return $pathInfo['extension'] ?? null;
    }

    /**
     * Get the file name
     * @param string $url
     * @return mixed|string|null
     */
    public function getFilename(string $url): ?string
    {
        $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH));
        return $pathInfo['filename'] ?? null;
    }

    /**
     * Checks if the given file is a supported image
     * @param string $path
     * @return bool
     */
    public function isSupportedImage(?string $path): bool
    {
        $path = parse_url($path, PHP_URL_PATH);
        $fileInfo = pathinfo($path);

        $extension = $fileInfo['extension'] ?? null;

        if ($extension === null) {
            return false;
        }

        $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

        return in_array(strtolower($extension), $validExtensions);
    }

    /**
     *  
     * @param string $imagePath
     * @param int $quality
     * @throws \BadrQaba\DevBoost\Exception\UnsupportedImageException
     * @throws \BadrQaba\DevBoost\Exception\InvalidCompressionQualityException
     * @return string
     */
    public function compressImage(string $imagePath, string $outputPath, int $quality): string
    {
        if (!file_exists($imagePath)) {
            throw new MediaNotFoundException("The media $imagePath does not exist");
        }

        $imageInfo = getimagesize($imagePath);
        $imageType = $imageInfo[2];

        if (!$this->isValidCompressionQuality($quality, $imageType)) {
            throw new InvalidCompressionQualityException("Unsupported image quality $quality");
        }

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $image = @imagecreatefromjpeg($imagePath);
                if ($image === false) {
                    throw new CompressionError("Unable to create image from JPEG file: $imagePath. Error: " . error_get_last()['message']);
                }
                imagejpeg($image, $outputPath, $quality);
                imagedestroy($image);
                gc_collect_cycles();
                return $outputPath;

            case IMAGETYPE_PNG:
                $image = @imagecreatefrompng($imagePath);
                if ($image === false) {
                    throw new CompressionError("Unable to create image from PNG file: $imagePath. Error: " . error_get_last()['message']);
                }
                imagepng($image, $outputPath, $quality);
                imagedestroy($image);
                gc_collect_cycles();
                return $outputPath;

            case IMAGETYPE_BMP:
                $image = @imagecreatefrombmp($imagePath);
                if ($image === false) {
                    throw new CompressionError("Unable to create image from BMP file: $imagePath. Error: " . error_get_last()['message']);
                }
                imagebmp($image, $outputPath);
                imagedestroy($image);
                gc_collect_cycles();
                return $outputPath;

            case IMAGETYPE_WEBP:
                $image = @imagecreatefromwebp($imagePath);
                if ($image === false) {
                    throw new CompressionError("Unable to create image from WebP file: $imagePath. Error: " . error_get_last()['message']);
                }
                imagewebp($image, $outputPath);
                imagedestroy($image);
                gc_collect_cycles();
                return $outputPath;

            default:
                throw new CompressionError("Unsupported image type: $imageType");
        }
    }

    /**
     * Checks if the given image is damaged
     * @param mixed $imagePath
     * @return bool
     */
    public function isImageCorrupt($imagePath)
    {
        $image = @imagecreatefromstring(file_get_contents($imagePath));
        return $image === false;
    }

    /**
     * Check is the image compression quality is valid
     * @param int $quality
     * @param int $imageType
     * @return bool
     */
    public function isValidCompressionQuality(int $quality, int $imageType): bool
    {

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                return $quality >= -1 && $quality <= 100;

            case IMAGETYPE_PNG:
                return $quality >= -1 && $quality <= 9;
            default:
                return true;
        }
    }

    /**
     * Convert given image to png
     * @param string $inputImagePath
     * @param string $outputImagePath
     * @param int $quality
     * @throws \BadrQaba\DevBoost\Exception\UnsupportedImageException
     * @return string
     */
    public function convertToPNG(string $inputImagePath, string $outputImagePath, int $quality = -1)
    {
        $imageInfo = getimagesize($inputImagePath);
        $imageType = $imageInfo[2];

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($inputImagePath);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($inputImagePath);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($inputImagePath);
                break;
            case IMAGETYPE_BMP:
                $image = imagecreatefrombmp($inputImagePath);
                break;
            case IMAGETYPE_WEBP:
                $image = imagecreatefromwebp($inputImagePath);
                break;
            default:
                throw new UnsupportedImageException();
        }
        imagepng($image, $outputImagePath, $quality);

        imagedestroy($image);

        return $outputImagePath;
    }

    /**
     * Get the folder size in bytes
     * @param string $folder
     * @return float|int
     */
    public function getFolderSize(string $folder): int
    {
        $size = 0;

        if (!is_dir($folder)) {
            return 0;
        }

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder, FilesystemIterator::SKIP_DOTS));

        foreach ($files as $file) {
            $size += $file->getSize();
        }

        return $size;
    }

    /**
     * Get the MimeType of a file
     * @param string $path
     * @return bool|string
     */
    public function getMimeType(string $path)
    {
        if (function_exists('finfo_file')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $path);
            finfo_close($finfo);
        } else {
            $mimeType = mime_content_type($path);
        }

        return $mimeType;
    }

    /**
     * Copy a file to a specific destination
     * @param string $sourcePath
     * @param string $destinationPath
     * @throws \RuntimeException
     * @return void
     */
    public function copyFile(string $sourcePath, string $destinationPath): void
    {
        if (!file_exists($sourcePath)) {
            throw new RuntimeException("The source file does not exist: $sourcePath");
        }

        $destinationDir = dirname($destinationPath);
        if (!is_dir($destinationDir)) {
            if (!mkdir($destinationDir, 0777, true) && !is_dir($destinationDir)) {
                throw new RuntimeException("Failed to create destination directory: $destinationDir");
            }
        }

        if (!copy($sourcePath, $destinationPath)) {
            throw new RuntimeException("Failed to copy file from $sourcePath to $destinationPath");
        }
    }

    /**
     * Delete a specific directory
     * @param string $dir
     * @return void
     */
    public function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        foreach (scandir($dir) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $filePath = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_dir($filePath)) {
                $this->deleteDirectory($filePath);
            } else {
                unlink($filePath);
            }
        }

        rmdir($dir);
    }
}
