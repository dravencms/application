<?php declare(strict_types = 1);

namespace Dravencms\Application\DI;

use Kdyby\Console\DI\ConsoleExtension;
use Nette;
use Nette\DI\Compiler;
use Nette\DI\Configurator;

/**
 * Class BaseExtension
 * @package Dravencms\Structure\DI
 */
class ApplicationExtension extends Nette\DI\CompilerExtension
{

    public function loadConfiguration()
    {
        $this->loadComponents();
    }


    protected function loadComponents()
    {
        $builder = $this->getContainerBuilder();
        foreach ($this->loadFromFile(__DIR__ . '/components.neon') as $i => $command) {
            $factoryDefinition = $builder->addFactoryDefinition($this->prefix('components.' . $i));
            if (is_string($command)) {
                $factoryDefinition->setImplement($command);
            } else {
                throw new \InvalidArgumentException;
            }
        }
    }
}
