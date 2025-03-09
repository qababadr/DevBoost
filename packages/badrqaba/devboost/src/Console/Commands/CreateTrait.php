<?php

namespace BadrQaba\DevBoost\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateTrait extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait {name : The name of the trait} {--folder= : The folder where the trait should be created}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a PHP trait in the App\\Http\\ folder or its subfolder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $folder = $this->option('folder') ?? '';

        // Ensure folder is empty or the user is specifying a subfolder
        if ($folder && !preg_match('/^[A-Za-z0-9_\/]*$/', $folder)) {
            $this->error('Invalid folder name.');
            return 1;
        }

        // Convert forward slashes to directory separator
        $folderPath = app_path('Http' . ($folder ? DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $folder) : ''));

        // Validate the trait name
        if (!preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $name)) {
            $this->error('Invalid trait name.');
            return 1;
        }

        // Create the folder if it doesn't exist
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
            $this->info("Created directory: $folderPath");
        }

        // Build the file path
        $filePath = $folderPath . DIRECTORY_SEPARATOR . $name . '.php';

        // Check if the file already exists
        if (File::exists($filePath)) {
            $this->error("The trait $name already exists in $folder.");
            return 1;
        }

        // Generate the trait content
        $namespace = 'App\\Http' . ($folder ? '\\' . str_replace('/', '\\', $folder) : '');
        $content = <<<PHP
<?php

namespace $namespace;

trait $name
{
    // Add your methods here
}
PHP;

        // Write the content to the file
        try {
            File::put($filePath, $content);
            $this->info("Trait $name created successfully at $filePath");
        } catch (Exception $e) {
            $this->error("Failed to create trait: " . $e->getMessage());
        }
    }
}
