<?php
namespace df\interfaces\extensions;

use df\interfaces\applications\IApplicationEvent;
use df\interfaces\processes\states\conditions\IBPStateCondition;

interface IExtensionBPS
{
    /**
     * @return IBPStateCondition[]
     */
    public function getConditions(): array;

    public function isApplicableEvent(IApplicationEvent $event): bool;
}
