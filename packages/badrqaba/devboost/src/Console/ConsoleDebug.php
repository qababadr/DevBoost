<?php

namespace BadrQaba\DevBoost\Console;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Helper trait that allows to interact with console with actions such as: 
 * - Can print messages
 */
trait ConsoleDebug
{
    private ConsoleOutput $console;

    /**
     * Prints a message to the console with line break, and optionally prints the same message to log file
     * @param string $message
     * @param bool $withLogFile
     * @return void
     */
    public function println(string $message, bool $withLogFile = false)
    {
        if (!isset($this->console)) {
            $this->console = new ConsoleOutput(ConsoleOutput::VERBOSITY_VERY_VERBOSE);
        }
        $this->console->writeln($message);
        if ($withLogFile) {
            Log::info($message);
        }
    }
}
