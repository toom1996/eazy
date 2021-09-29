<?php

namespace eazy\console;

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


    public function log($level, string|array $message)
    {
        $level = 'error';
        $template = sprintf('<%s>[%s]</>', $level, strtoupper($level));
        $implodedTags = '';
        foreach ($tags as $value) {
            $implodedTags .= (' [' . $value . ']');
        }

        $this->output->writeln(sprintf($template . $implodedTags . ' %s', $message));
    }
}