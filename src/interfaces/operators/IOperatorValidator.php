<?php
namespace df\interfaces\operators;

use df\interfaces\applications\IApplicationEvent;
use df\interfaces\applications\samples\states\fields\IHaveStateField;
use extas\interfaces\IHasValue;
use extas\interfaces\IItem;

interface IOperatorValidator extends IItem, IHaveStateField, IHasValue
{
    public const SUBJECT = 'df.operator.validator';

    public const FIELD__APPLICATION_EVENT = 'app_event';

    public function getAppEvent(): ?IApplicationEvent;
    public function isValid(): bool;
}
