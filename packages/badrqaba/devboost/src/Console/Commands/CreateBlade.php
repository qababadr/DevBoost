<?php

namespace BadrQaba\DevBoost\Console\Commands;

use Illuminate\Console\Command;

class CreateBlade extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:blade {name} {--folder=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates an empty blade file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $folder = $this->option('folder');

        $views_path = config('view.paths')[0] . DIRECTORY_SEPARATOR;

        if (preg_match('/^[a-zA-Z0-9_-]+$/', $name) && preg_match('/^[a-zA-Z0-9_-]+$/', $folder)) {

            $folderPath = $views_path . $folder;

            if (!is_dir($folderPath)) {
                if (!mkdir($folderPath, 0777, true)) {
                    $this->error("Failed to create folder $folder.");
                    return Command::FAILURE;
                }
            }

            $filePath = $folderPath . DIRECTORY_SEPARATOR . $name . '.blade.php';

            if (file_exists($filePath)) {
                $this->error("The blade file $name.blade.php already exists.");
                return Command::FAILURE;
            }

            // Create the file with some default content
            $content = "<div>\n    {{-- Shine as you are awesome --}}\n</div>";
            if (file_put_contents($filePath, $content)) {
                $this->info('The blade file was created successfully!');
                return Command::SUCCESS;
            } else {
                $this->error('Failed to create the blade file.');
                return Command::FAILURE;
            }
        } else {
            $this->error('Invalid file or folder name.');
            return Command::FAILURE;
        }
    }
}
