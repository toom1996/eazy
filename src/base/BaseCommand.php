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
    protected string $help = '';

    /**
     * Command augument.
     * @var array
     */
    protected array $arguments = [
    ];

    protected array $options = [];

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->setName($this->name);
        $this->setDescription($this->description);
        $this->setHelp($this->help);

        foreach ($this->options as $option) {
            $this->addOption(...$option);
        }

        foreach ($this->arguments as $argument) {
            $this->addArgument(...$argument);
        }
    }
}