<?php

namespace eazy\base;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

class BaseCommand extends Command
{
    /**
     * Command name.
     * @var string
     */
    protected string $name;

    /**
     * Command Description.
     * @var string
     */
    protected string $description;

    /**
     * Command help.
     * @var string
     */
    protected string $help;

    /**
     * Command augument.
     * @var array
     */
    protected array $arguments = [
        ['name', InputArgument::REQUIRED, 'what\'s model you want to create ?'],
        ['optional_argument', InputArgument::OPTIONAL, 'this is a optional argument'],
    ];

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->setName($this->name);
        $this->setDescription($this->description);
        $this->setHelp($this->help);

        foreach ($this->arguments as $argument) {
            $this->addArgument(...$argument);
        }
    }
}