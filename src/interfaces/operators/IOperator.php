<?php
namespace df\interfaces\operators;

use extas\interfaces\IDispatcherWrapper;
use extas\interfaces\IHaveUUID;
use extas\interfaces\IItem;

interface IOperator extends IItem, IHaveUUID, IDispatcherWrapper
{
    public const SUBJECT = 'df.operator';
}
