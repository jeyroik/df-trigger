<?php
namespace df\components\extensions;

use df\interfaces\applications\samples\states\fields\IStateField;
use df\interfaces\extensions\IExtensionBPTransition;
use df\interfaces\fields\IFieldPluginTarget;
use df\interfaces\processes\transitions\fields\IBPTransitionField;
use df\interfaces\processes\transitions\IBPTransition;
use extas\components\extensions\Extension;

class ExtensionBPTransition extends Extension implements IExtensionBPTransition
{
    public function getTargetFields(IBPTransition $bpt = null): array
    {
        /**
         * @var IBPTransitionField[] $bptFields
         */
        $bptFields = $this->bptFields()->all([
            IBPTransitionField::FIELD__TRANSITION_ID => $bpt->getId()
        ]);

        $bptFieldsById = [];

        foreach ($bptFields as $bptField) {

            /**
             * @var IFieldPluginTarget[] $fpts
             * @var IBPTransitionField $bptField
             */
            $fpts = $this->pluginsTargets()->all([
                IFieldPluginTarget::FIELD__TARGET_ID => $bptField->getId()
            ]);

            foreach ($fpts as $fpt) {
                $fp = $fpt->getPlugin();
                $plugin = $fp->buildClassWithParameters();
                $plugin($bptField);
            }

            $bptFieldsById[$bptField->getStateFieldId()] = $bptField->getValue();
        }

        $targetBPS = $bpt->getTarget();

        /**
         * @var IStateField[] $tbpsFields
         */
        $tbpsFields = $this->statesFields()->all([
            IStateField::FIELD__APPS_STATE_ID => $targetBPS->getAppSampleStateId()
        ]);

        $targetBody = [];

        foreach ($tbpsFields as $field) {
            $targetBody[$field->getName()] = $bptFieldsById[$field->getId()];
        }

        return $targetBody;
    }
}
