<?php
/**
 * Created by PhpStorm.
 * User: sadam
 * Date: 10/14/21
 * Time: 9:56 PM
 */

namespace Dravencms\Application\Webloader\Filter;

use ScssPhp\ScssPhp\Compiler;

class ScssFilter
{

    /** @var Compiler|null */
    private $sc;


    public function __construct(?Compiler $sc = null)
    {
        $this->sc = $sc;
    }


    private function getScssC(): Compiler
    {
        // lazy loading
        if (empty($this->sc)) {
            $this->sc = new Compiler();
        }

        return $this->sc;
    }


    public function __invoke(string $code, \WebLoader\Compiler $loader, string $file): string
    {
        $file = (string) $file;

        if (pathinfo($file, PATHINFO_EXTENSION) === 'scss') {
            $this->getScssC()->setImportPaths(['', pathinfo($file, PATHINFO_DIRNAME) . '/']);
            return $this->getScssC()->compileString($code)->getCss();
        }

        return (string) $code;
    }
}
