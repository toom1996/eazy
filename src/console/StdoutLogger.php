<?php

namespace eazy\console;

use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\ConsoleOutput;

class StdoutLogger
{
    /**
     * @var \Symfony\Component\Console\Output\ConsoleOutput
     */
    private $output = null;

    public function __construct()
    {
        $this->output = new ConsoleOutput();
    }


    public function log(string $level, string|array $message)
    {
        $template = sprintf('<%s>[%s]</>', $level, strtoupper($level));
        $this->output->writeln(sprintf($template . ' %s', $message));
    }
}