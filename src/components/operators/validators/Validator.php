<?php
namespace df\components\operators\validators;

use df\components\applications\samples\states\fields\THasStateField;
use df\interfaces\applications\IApplicationEvent;
use df\interfaces\operators\IOperatorValidator;
use extas\components\Item;
use extas\components\THasValue;

abstract class Validator extends Item implements IOperatorValidator
{
    use THasValue;
    use THasStateField;

    public function getAppEvent(): ?IApplicationEvent
    {
        return $this->config[static::FIELD__APPLICATION_EVENT] ?? null;
    }

    protected function getAppEventValue(): mixed
    {
        return $this->getAppEvent()[$this->getStateField()->getName()] ?? null;
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
