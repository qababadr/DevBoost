<?php

namespace BadrQaba\DevBoost\Database\Core;

use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait DatabaseToolkit
{

    /**
     * Wipes out all data from a table and resets its auto-increment ID.
     *
     * @param string $tableName The table name.
     */
    public function wipeTable(string $tableName)
    {
        try {
            if (!Schema::hasTable($tableName)) {
                throw new InvalidArgumentException("The table name $tableName does not exist.");
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            DB::table($tableName)->truncate();

            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        } catch (Exception $exp) {
            throw $exp;
        }
    }
}
