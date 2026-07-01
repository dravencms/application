<?php declare(strict_types = 1);

namespace Dravencms\Components\BaseControl;

/**
 * Copyright (C) 2016 Adam Schubert <adam.schubert@sg1-game.net>.
 */

use Nette\Application\UI\Control;
use Nette\Http\IResponse;

class BaseControl extends Control
{
    private const IS_ALLOWED_ATTRIBUTE = 'Dravencms\User\Attributes\IsAllowed';

    public function __construct()
    {
    }

    public function checkRequirements(\ReflectionClass|\ReflectionMethod $element): void
    {
        parent::checkRequirements($element);

        if (!$element instanceof \ReflectionMethod || !$attributes = $element->getAttributes(self::IS_ALLOWED_ATTRIBUTE)) {
            return;
        }

        $presenter = $this->getPresenter();
        if (!property_exists($presenter, 'authorizator')) {
            return;
        }

        $requirement = $attributes[0]->newInstance();
        if (!$presenter->authorizator->isAllowed(null, $requirement->resource, $requirement->operation)) {
            $this->error('FORBIDDEN ' . $requirement->resource . ':' . $requirement->operation, IResponse::S403_FORBIDDEN);
        }
    }
}
