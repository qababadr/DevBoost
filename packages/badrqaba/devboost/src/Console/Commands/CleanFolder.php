<?php

namespace BadrQaba\DevBoost\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanFolder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean-folder {folder} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all files and folders in a specific folder';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $folder = $this->argument('folder');

        // Get the full path to the folder using storage_path()
        $fullPath = storage_path($folder);

        // Ensure folder exists in the filesystem
        if (!is_dir($fullPath)) {
            $this->error("The folder $fullPath is not valid or does not exist.");
            return Command::FAILURE;
        }

        $isInteractive = $this->input->isInteractive();

        if ($isInteractive && !$this->option('force')) {
            if (!$this->confirm("Are you sure you want to clean the folder: $fullPath ?\n This action cannot be undone.")) {
                $this->info('Operation cancelled.');
                return Command::FAILURE;
            }
        } else {
            $this->info('Skipping confirmation prompt (non-interactive environment or --force used).');
        }

        // Get files in the folder using PHP functions since we are working directly with storage_path()
        $files = glob($fullPath . '/*'); // Get all files in the folder

        foreach ($files as $file) {
            if (is_file($file)) {
                if (unlink($file)) {
                    $this->info("The file $file has been deleted");
                } else {
                    $this->error("Unable to delete $file");
                }
            }
        }

        // Get subdirectories and delete them
        $subDirs = glob($fullPath . '/*', GLOB_ONLYDIR); // Get all subdirectories
        foreach ($subDirs as $subDir) {
            if (rmdir($subDir)) {
                $this->info("The directory $subDir has been deleted");
            } else {
                $this->error("The directory $subDir could not be deleted");
            }
        }

        // Check if the folder is empty
        if (count(glob($fullPath . '/*')) == 0) {
            $this->info("The $folder is now empty");
            return Command::SUCCESS;
        } else {
            $this->error("Could not delete all files and folders in $folder");
            return Command::FAILURE;
        }
    }
}
