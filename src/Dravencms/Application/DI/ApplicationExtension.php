<?php declare(strict_types = 1);

namespace Dravencms\Base\DI;

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
        $builder = $this->getContainerBuilder();


        $this->loadComponents();
    }

    public function beforeCompile()
    {
        $builder = $this->getContainerBuilder();
        $cms = $builder->getDefinition($this->prefix('base'));

    }


    protected function loadComponents()
    {
        $builder = $this->getContainerBuilder();
        foreach ($this->loadFromFile(__DIR__ . '/components.neon') as $i => $command) {
            $cli = $builder->addDefinition($this->prefix('components.' . $i))
                ->setInject(FALSE); // lazy injects
            if (is_string($command)) {
                $cli->setImplement($command);
            } else {
                throw new \InvalidArgumentException;
            }
        }
    }
}
