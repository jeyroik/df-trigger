<?php
namespace df\components\extensions;

use df\interfaces\applications\IApplicationEvent;
use df\interfaces\extensions\IExtensionBPS;
use df\interfaces\extensions\IExtensionCondition;
use df\interfaces\processes\states\conditions\IBPStateCondition;
use df\interfaces\processes\states\IBPState;
use extas\components\extensions\Extension;
use extas\interfaces\repositories\IRepository;

/**
 * @method IRepository bpsConditions()
 */
class ExtensionBPS extends Extension implements IExtensionBPS
{
    /**
     * @return IBPStateCondition[]
     */
    public function getConditions(IBPState $state = null): array
    {
        return $this->bpsConditions()->all([
            IBPStateCondition::FIELD__BP_STATE_ID => $state->getId()
        ]);
    }

    public function isApplicableEvent(IApplicationEvent $event, IBPState $state = null): bool
    {
        $conditions = $this->getConditions($state);
        $valid = true;

        foreach ($conditions as $condition) {
            /**
             * @var IExtensionCondition $condition
             */
            if ($condition->isValid($event)) {
                continue;
            }

            $valid = false;
            break;
        }

        return $valid;
    }
}
