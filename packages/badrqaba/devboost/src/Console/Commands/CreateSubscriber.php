<?php

namespace BadrQaba\DevBoost\Console\Commands;

use Illuminate\Console\Command;

class CreateSubscriber extends Command
{
    protected $signature = 'make:subscriber {name} {--folder=}';
    protected $description = 'Create an event subscriber class';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $folder = $this->option('folder');
        // Use DIRECTORY_SEPARATOR for platform-agnostic directory paths
        $subscribers_path = app_path('Subscribers');

        if (preg_match('/^[a-zA-Z0-9_-]+$/', $name) && preg_match('/^[a-zA-Z0-9_-]+$/', $folder)) {
            // Create the full folder path using DIRECTORY_SEPARATOR
            $folderPath = $subscribers_path . DIRECTORY_SEPARATOR . $folder;

            if (!is_dir($folderPath)) {
                if (!mkdir($folderPath, 0777, true)) {
                    $this->error("Failed to create folder $folder.");
                    return Command::FAILURE;
                }
            }

            $filePath = $folderPath . DIRECTORY_SEPARATOR . "$name.php";

            if (file_exists($filePath)) {
                $this->error("The subscriber class $name already exists!");
                return Command::FAILURE;
            } else {
                $content = "<?php\n"
                    . "namespace App\\Subscribers\\Models;\n\n"
                    . "use Illuminate\\Events\\Dispatcher;\n\n"
                    . "class $name {\n\n"
                    . "   public function subscribe(Dispatcher \$events)\n"
                    . "   {\n"
                    . "   \n"
                    . "   }\n"
                    . "}";
                file_put_contents($filePath, $content);
                $this->info('The subscriber class is created successfully!');
                return Command::SUCCESS;
            }
        } else {
            $this->error('File or folder name is not valid');
            return Command::FAILURE;
        }
    }
}
