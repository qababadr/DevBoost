<?php

namespace BadrQaba\DevBoost\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use BadrQaba\DevBoost\Database\Core\DatabaseToolkit;

class WipeTable extends Command
{
    use DatabaseToolkit;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wipe {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate a database table and reset the auto increment id to 0';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tableName = $this->argument('table');

        if (!$this->confirm("Are you sure you want to wipe the table: $tableName? This action cannot be undone.")) {
            $this->info('Operation cancelled.');
            return Command::SUCCESS;
        }

        $this->info("Wiping table: $tableName");

        try {
            $this->wipeTable($tableName);
            $this->info("Table $tableName has been wiped and reset.");
            return Command::SUCCESS;
        } catch (Exception $exp) {
            $this->error("Error occurred while wiping out the table $tableName: {$exp->getMessage()}");
            return Command::FAILURE;
        }
    }
}
