<?php declare(strict_types = 1);

namespace Dravencms;

use Nette\Application\Helpers;
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
        $layout = $this->getLayoutName();
        if (preg_match('#/|\\\\#', (string) $layout)) {
            return [$layout];
        }
        [$module, $presenter] = Helpers::splitName($this->getName());
        $exploded = explode(':', $module);
        $submoduleName = end($exploded);
        $moduleName = current($exploded);
        $dir = dirname(static::getReflection()->getFileName());
        $dir = is_dir("$dir/templates") ? $dir : dirname($dir);
        $list = [
            "$dir/templates/$submoduleName/$presenter/@$layout.latte",
            "$dir/templates/$submoduleName/$presenter.@$layout.latte",
        ];
        do {
            $list[] = "$dir/templates/@$layout.latte";
            $dir = dirname($dir);
        } while ($dir && $module && ([$module] = Helpers::splitName($module)));


        $list[] = realpath(__DIR__ . "/..") . '/' . $moduleName . "Module/templates/@$layout.latte";

        return $list;
    }

    public function getLayoutName(): string {
        return $this->getLayout() ? $this->getLayout() : 'layout';
    }

    /**
     * Formats view template file names.
     * @return array
     */
    public function formatTemplateFiles(): array
    {
        [$module, $presenter] = Helpers::splitName($this->getName());
        $exploded = explode(':', $module);
        $submoduleName = end($exploded);
        $dir = dirname(static::getReflection()->getFileName());
        $dir = is_dir("$dir/templates") ? $dir : dirname($dir);
        return [
            "$dir/templates/$submoduleName/$presenter/$this->view.latte",
            "$dir/templates/$submoduleName/$presenter.$this->view.latte",
        ];
    }
}
