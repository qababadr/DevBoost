<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

//php artisan test --testsuite=Feature  --filter CommandsTest
class CommandsTest extends TestCase
{
    private ToolkitClassUser $media;
    private string $downloadedImagePath;
    private string $pngImageUrl;
    private string $jpegImageUrl;
    private string $textFile;

    protected function setUp(): void
    {
        parent::setUp();
        $this->media = new ToolkitClassUser();
        $this->pngImageUrl = "https://qababadr.com/assets/img/favicons/switched_colors/android-chrome-192x192.png";
        $this->jpegImageUrl = "https://qababadr.com/assets/img/anzim-mockup.jpg";
        $this->textFile = storage_path('temp/test.txt');
    }

    protected function tearDown(): void
    {

        if (isset($this->downloadedImagePath) && file_exists($this->downloadedImagePath)) {
            unlink($this->downloadedImagePath);
        }
        parent::tearDown();
    }

    public function test_CleanFolder_command_should_clean_folder()
    {
        $fileCopyPath = storage_path('temp/copy_destination/copied_file.txt');
        $folderDestination = storage_path('temp/copy_destination');

        $this->media->copyFile(
            $this->textFile,
            $fileCopyPath
        );

        $this->assertFileExists($fileCopyPath, "The copied file should exist");

        Artisan::call('clean-folder', [
            'folder' => 'temp/copy_destination',
            '--force' => true,
        ]);

        $this->assertFileDoesNotExist($fileCopyPath, "The folder temp/copy_destination should not exist");

        rmdir($folderDestination);
    }

    public function test_CreateBlade_command_should_create_blade_file()
    {
        $outputFolder = 'landing_page';
        $bladeName = 'Home';

        $result = Artisan::call('make:blade', [
            'name' => $bladeName,
            '--folder' => $outputFolder,
        ]);

        $this->assertEquals($result, Command::SUCCESS, "The command should give SUCCESS result");

        $views_path = config('view.paths')[0] . DIRECTORY_SEPARATOR;
        $bladeDestination = $views_path . $outputFolder . DIRECTORY_SEPARATOR . "$bladeName.blade.php";

        $this->assertFileExists($bladeDestination, "The file $bladeName.blade.php should exist");

        unlink($bladeDestination);
        rmdir($views_path . $outputFolder);
    }

    public function test_CreateBlade_command_should_give_failure_status_when_passing_wrong_params()
    {
        $outputFolder = 'landing_page';
        $bladeName = 'Home.blade.php';

        $result = Artisan::call('make:blade', [
            'name' => $bladeName,
            '--folder' => $outputFolder,
        ]);

        $this->assertEquals($result, Command::FAILURE, "The command should give FAILURE result");
    }


    public function test_CreateLayout_command_should_create_layout_file()
    {
        $outputFolder = 'landing_page_layout';
        $bladeName = 'Dashboard';

        $result = Artisan::call('make:layout', [
            'name' => $bladeName,
            '--folder' => $outputFolder,
        ]);

        $this->assertEquals($result, Command::SUCCESS, "The command should give SUCCESS result");

        $views_path = config('view.paths')[0] . DIRECTORY_SEPARATOR;
        $bladeDestination = $views_path . $outputFolder . DIRECTORY_SEPARATOR . "$bladeName.blade.php";

        $this->assertFileExists($bladeDestination, "The file $bladeName.blade.php should exist");

        unlink($bladeDestination);
        rmdir($views_path . $outputFolder);
    }

    public function test_CreateLayout_command_should_give_failure_status_when_passing_wrong_params()
    {
        $outputFolder = 'landing_page_layout';
        $bladeName = 'Dashboard.blade.php';

        $result = Artisan::call('make:layout', [
            'name' => $bladeName,
            '--folder' => $outputFolder,
        ]);

        $this->assertEquals($result, Command::FAILURE, "The command should give FAILURE result");
    }


    public function test_CreateSubscriber_command_should_create_subscriber_class()
    {
        $outputFolder = 'event_subscribers';
        $subscriberName = 'UserRegisteredSubscriber';

        $result = Artisan::call('make:subscriber', [
            'name' => $subscriberName,
            '--folder' => $outputFolder,
        ]);

        $this->assertEquals(Command::SUCCESS, $result, "The command should give SUCCESS result");

        $subscribers_path = app_path('Subscribers') . DIRECTORY_SEPARATOR . $outputFolder . DIRECTORY_SEPARATOR;
        $subscriberDestination = $subscribers_path . "$subscriberName.php";

        $this->assertFileExists($subscriberDestination, "The file $subscriberName.php should exist");

        unlink($subscriberDestination);
        rmdir($subscribers_path);
    }


    public function test_CreateSubscriber_command_should_give_failure_status_when_passing_wrong_params()
    {
        $outputFolder = 'event_subscribers';
        $subscriberName = 'UserRegisteredSubscriber.php';

        $result = Artisan::call('make:subscriber', [
            'name' => $subscriberName,
            '--folder' => $outputFolder,
        ]);

        $this->assertEquals($result, Command::FAILURE, "The command should give FAILURE result");
    }


    public function test_CreateTrait_command_should_create_trait()
    {
        $outputFolder = 'Traits';
        $traitName = 'ExampleTrait';

        $result = Artisan::call('make:trait', [
            'name' => $traitName,
            '--folder' => $outputFolder,
        ]);

        $this->assertEquals(Command::SUCCESS, $result, "The command should give SUCCESS result");

        $traits_path = app_path('Http' . DIRECTORY_SEPARATOR . $outputFolder . DIRECTORY_SEPARATOR);
        $traitDestination = $traits_path . "$traitName.php";

        $this->assertFileExists($traitDestination, "The file $traitName.php should exist in $traits_path");

        unlink($traitDestination);

        if (count(glob($traits_path . '*')) === 0) {
            rmdir($traits_path);
        }
    }


    public function test_CreateTrait_command_should_give_failure_status_when_passing_wrong_params()
    {
        $outputFolder = 'traits';
        $traitName = 'ExampleTrait.php';

        $result = Artisan::call('make:trait', [
            'name' => $traitName,
            '--folder' => $outputFolder,
        ]);

        $this->assertEquals($result, Command::FAILURE, "The command should give FAILURE result");
    }
}
