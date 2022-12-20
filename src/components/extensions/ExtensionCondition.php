<?php
namespace df\components\extensions;

use df\interfaces\applications\IApplicationEvent;
use df\interfaces\extensions\IExtensionCondition;
use df\interfaces\operators\IOperator;
use df\interfaces\operators\IOperatorValidator;
use df\interfaces\processes\states\conditions\IBPStateCondition;
use extas\components\exceptions\MissedOrUnknown;
use extas\components\extensions\Extension;
use extas\interfaces\repositories\IRepository;

/**
 * @method IRepository operators()
 */
class ExtensionCondition extends Extension implements IExtensionCondition
{
    public function isValidFor(IApplicationEvent $event, IBPStateCondition $condition = null): bool
    {
        /**
         * @var IOperator $operator
         */
        $operator = $this->operators()->one([IOperator::FIELD__NAME => $condition->getOperator()]);

        if (!$operator) {
            throw new MissedOrUnknown('condition operator "' . $condition->getOperator() . '"');
        }

        $validator = $operator->buildClassWithParameters([
            IOperatorValidator::FIELD__APPLICATION_EVENT => $event,
            IOperatorValidator::FIELD__STATE_FIELD_ID => $condition->getStateFieldId(),
            IOperatorValidator::FIELD__VALUE => $condition->getValue()
        ]);

        return $validator->isValid();
    }
}
