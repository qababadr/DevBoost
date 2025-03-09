<?php

namespace Tests\Feature;

use Exception;
use Tests\TestCase;

//php artisan test --testsuite=Feature  --filter MediaToolkitTest
class MediaToolkitTest extends TestCase
{
    private ToolkitClassUser $media;
    private string $downloadedImagePath;
    private string $pngImageUrl;
    private string $jpegImageUrl;
    private string $damagedImagePath;
    private string $textFile;

    protected function setUp(): void
    {
        parent::setUp();
        $this->media = new ToolkitClassUser();
        $this->pngImageUrl = "https://qababadr.com/assets/img/favicons/switched_colors/android-chrome-192x192.png";
        $this->jpegImageUrl = "https://qababadr.com/assets/img/anzim-mockup.jpg";
        $this->damagedImagePath = storage_path('temp/damaged_image.jpg');
        $this->textFile = storage_path('temp/test.txt');
    }

    protected function tearDown(): void
    {

        if (isset($this->downloadedImagePath) && file_exists($this->downloadedImagePath)) {
            unlink($this->downloadedImagePath);
        }
        parent::tearDown();
    }

    public function test_MedialHandler_can_curl_download_image()
    {
        try {
            $this->downloadedImagePath = $this->media->curlDownload(
                url: $this->pngImageUrl,
                outputFolder: storage_path('temp')
            );

            $this->assertNotNull($this->downloadedImagePath, 'The image path should not be null');
            $this->assertFileExists($this->downloadedImagePath, 'The image should exist');
        } catch (Exception $exp) {
            $this->fail("The method threw an exception: {$exp->getMessage()} {$exp->getTraceAsString()}");
        }
    }

    public function test_MedialHandler_can_download_image()
    {
        try {
            $this->downloadedImagePath = $this->media->download(
                url: $this->pngImageUrl,
                outputFolder: storage_path('temp')
            );

            $this->assertNotNull($this->downloadedImagePath, 'The image path should not be null');
            $this->assertFileExists($this->downloadedImagePath, 'The image should exist');
        } catch (Exception $exp) {
            $this->fail("The method threw an exception: {$exp->getMessage()} {$exp->getTraceAsString()}");
        }
    }

    public function test_isImageCorrupt_should_give_true_as_the_image_is_corrupt()
    {
        $this->assertFileExists($this->damagedImagePath, 'The damaged image should exist');
        $isCorrupt = $this->media->isImageCorrupt($this->damagedImagePath);
        $this->assertTrue($isCorrupt, 'The method should give true cause the image is corrupt');
    }

    public function test_isImageCorrupt_should_give_false_as_the_image_is_not_corrupt()
    {
        $this->downloadedImagePath = $this->media->download(
            url: $this->pngImageUrl,
            outputFolder: storage_path('temp')
        );

        $this->assertNotNull($this->downloadedImagePath, 'The image path should not be null');
        $this->assertFileExists($this->downloadedImagePath, 'The image should exist');

        $isCorrupt = $this->media->isImageCorrupt($this->downloadedImagePath);
        $this->assertFalse($isCorrupt, 'The method should give false cause the image is not corrupt');
    }

    public function test_compressImage_should_compress_image()
    {
        $this->downloadedImagePath = $this->media->download(
            url: $this->pngImageUrl,
            outputFolder: storage_path('temp')
        );

        $this->assertNotNull($this->downloadedImagePath, 'The image path should not be null');
        $this->assertFileExists($this->downloadedImagePath, 'The image should exist');

        $downloadedInfo = pathinfo(parse_url($this->downloadedImagePath, PHP_URL_PATH));

        ini_set('memory_limit', '2g');

        $compressedImage = $this->media->compressImage(
            imagePath: $this->downloadedImagePath,
            outputPath: storage_path('temp') . '/' . $downloadedInfo['filename'] . '_compressed' . '.' . strtolower($downloadedInfo['extension']),
            quality: 5
        );

        $this->assertFileExists($compressedImage, 'The compressed image should exist');

        $isCorrupt = $this->media->isImageCorrupt($compressedImage);
        $this->assertFalse($isCorrupt, 'The method should give false because the image is not corrupt');

        $originalImageSize = filesize($this->downloadedImagePath);
        $compressedImageSize = filesize($compressedImage);

        $this->assertTrue($compressedImageSize > 0, 'The compressed image size should be greater than zero');
        $this->assertTrue($compressedImageSize < $originalImageSize, 'The compressed image should be smaller than the original image');

        unlink($compressedImage);
    }

    public function test_getFileExtension_should_give_png_extension()
    {
        $result = $this->media->getFileExtension($this->pngImageUrl);

        $this->assertEquals('png', $result, "The file extension should be png");
    }

    public function test_isSupportedImage_should_detected_supported_files()
    {
        $this->assertTrue($this->media->isSupportedImage($this->pngImageUrl), "The image should be supported");
        $this->assertFalse($this->media->isSupportedImage($this->textFile), "The text file should be unsupported");
    }

    public function test_isValidCompressionQuality_should_detected_valid_compression_value()
    {
        $validQuality = 80;
        $invalidQuality = 200;
        $imageType = IMAGETYPE_JPEG;

        $this->assertTrue($this->media->isValidCompressionQuality($validQuality, $imageType), "80 should be a valid compression quality");
        $this->assertFalse($this->media->isValidCompressionQuality($invalidQuality, $imageType), "200 should be invalid compression quality");
    }

    public function test_convertToPNG_should_convert_from_jpg_to_png()
    {
        $this->downloadedImagePath = $this->media->download(
            url: "https://qababadr.com/assets/img/anzim-mockup.jpg",
            outputFolder: storage_path('temp')
        );

        $this->assertNotNull($this->downloadedImagePath, 'The image path should not be null');
        $this->assertFileExists($this->downloadedImagePath, 'The image should exist');

        $convertedPNG = $this->media->convertToPNG(
            $this->downloadedImagePath,
            storage_path('temp') . '/' . md5("converted") . '_converted' . '.png'
        );

        $this->assertNotNull($convertedPNG, 'The converted image path should not be null');
        $this->assertFileExists($convertedPNG, 'The converted image should exist');

        $convertedExtension = $this->media->getFileExtension($convertedPNG);

        $this->assertEquals('png', $convertedExtension, "The file extension should be png");
        $this->assertEquals('image/png', $this->media->getMimeType($convertedPNG));

        if (file_exists($convertedPNG)) {
            unlink($convertedPNG);
        }
    }

    public function test_getMimeType_should_give_a_valid_mime_type()
    {
        $this->assertEquals('text/plain', $this->media->getMimeType($this->textFile));
    }

    public function test_copyFile_should_copy_a_file_to_specific_folder_and_delete_it_afterward()
    {
        $fileCopyPath = storage_path('temp/copy_destination/copied_file.txt');
        $folderDestination = storage_path('temp/copy_destination');

        $this->media->copyFile(
            $this->textFile,
            $fileCopyPath
        );

        $this->assertFileExists($fileCopyPath, "The copied file should exist");

        $this->media->deleteDirectory($folderDestination);

        $this->assertFileDoesNotExist($folderDestination, "The copy destination should not exist");
    }
}
