<?php

namespace BadrQaba\DevBoost\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MigrateAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all files, skipping errors';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get all migration files dynamically
        $migrationFiles = File::files(database_path('migrations'));

        $successCount = 0;
        $errorCount = 0;

        foreach ($migrationFiles as $file) {
            $fileName = $file->getFilename();
            try {
                $this->call('migrate', [
                    '--path' => "database/migrations/{$fileName}"
                ]);
                $this->info("✅ Migrated Successfully: {$fileName}");
                $successCount++;
            } catch (\Throwable $th) {
                $this->error("❌ Error Migrating: {$fileName} - {$th->getMessage()}");
                $errorCount++;
            }
        }

        $this->info("✅ {$successCount} migrations ran successfully.");
        $this->info("❌ {$errorCount} migrations failed.");
        return 0;
    }
}
