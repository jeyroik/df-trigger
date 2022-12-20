<?php
namespace df\interfaces\extensions;

use df\interfaces\applications\IApplicationEvent;

interface IExtensionCondition
{
    public function isValidFor(IApplicationEvent $event): bool;
}
