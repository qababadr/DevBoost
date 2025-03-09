<?php

namespace BadrQaba\DevBoost\Console\Commands;

use Illuminate\Console\Command;

class CreateLayout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:layout {name} {--folder=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates an empty layout file';

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
                $this->error("The layout file $name.blade.php already exists.");
                return Command::FAILURE;
            }

            $content = "<div>\n    {{-- From skeleton to a great creation --}}\n</div>";
            if (file_put_contents($filePath, $content)) {
                $this->info('The layout file was created successfully!');
                return Command::SUCCESS;
            } else {
                $this->error('Failed to create the layout file.');
                return Command::FAILURE;
            }
        } else {
            $this->error('Invalid file or folder name.');
            return Command::FAILURE;
        }
    }
}
