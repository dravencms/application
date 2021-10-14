<?php declare(strict_types = 1);

namespace Dravencms;

use Nette\Security\IIdentity;
use WebLoader\Nette\LoaderFactory;
use Nette\Application\UI\Presenter;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Presenter
{
    /** @var LoaderFactory @inject */
    public $webLoader;

    public function startup(): void
    {
        $this->invalidLinkMode = self::INVALID_LINK_EXCEPTION;
        parent::startup();
    }

    /**
     * Formats layout template file names.
     * @return array
     */
    public function formatLayoutTemplateFiles(): array
    {
        $name = $this->getName();
        $presenter = substr($name, strrpos(':' . $name, ':'));
        $className = trim(str_replace($presenter . 'Presenter', '', get_class($this)), '\\');
        $exploded = explode('\\', $className);
        $moduleName = str_replace('Module', '', end($exploded));
        $layout = $this->getLayoutName();
        $dir = dirname($this->getReflection()->getFileName());
        $dir = is_dir("$dir/templates") ? $dir : dirname($dir);
        $list = [
            "$dir/templates/$moduleName/$presenter/@$layout.latte",
            "$dir/templates/$moduleName/$presenter.@$layout.latte",
        ];
        do {
            $list[] = "$dir/templates/@$layout.latte";
            $dir = dirname($dir);
        } while ($dir && ($name = substr($name, 0, strrpos($name, ':'))));

        $list[] = realpath(__DIR__ . "/..") . '/' . $this->getNamespace() . "Module/templates/@$layout.latte";

        return $list;
    }

    private function getLayoutName(): string{
        return $this->layout ? $this->layout : 'layout';
    }

    /**
     * Formats view template file names.
     * @return array
     */
    public function formatTemplateFiles(): array
    {
        $name = $this->getName();
        $presenter = substr($name, strrpos(':' . $name, ':'));
        $className = trim(str_replace($presenter . 'Presenter', '', get_class($this)), '\\');
        $exploded = explode('\\', $className);
        $moduleName = str_replace('Module', '', end($exploded));
        $dir = dirname($this->getReflection()->getFileName());
        $dir = is_dir("$dir/templates") ? $dir : dirname($dir);
        return [
            "$dir/templates/$moduleName/$presenter/$this->view.latte",
            "$dir/templates/$moduleName/$presenter.$this->view.latte",
        ];
    }

    /**
     * @return mixed
     */
    public function getNamespace(): string
    {
        return $this->getUser()->getStorage()->getNamespace();
    }

    /**
     * @return \Nette\Security\IIdentity|NULL
     */
    public function getUserEntity(): ?IIdentity
    {
        return $this->getUser()->getIdentity();
    }

    /**
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->getUser()->isLoggedIn();
    }
}
