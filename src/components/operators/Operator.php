<?php
namespace df\components\operators;

use df\interfaces\operators\IOperator;
use extas\components\Item;
use extas\components\TDispatcherWrapper;
use extas\components\THasStringId;

class Operator extends Item implements IOperator
{
    use THasStringId;
    use TDispatcherWrapper;
    
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
