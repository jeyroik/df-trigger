<?php
namespace df\components\extensions;

use df\interfaces\extensions\IExtensionBPTField;
use df\interfaces\fields\IFieldPluginTarget;
use df\interfaces\processes\transitions\fields\IBPTransitionField;
use extas\components\extensions\Extension;
use extas\interfaces\repositories\IRepository;

/**
 * @method IRepository pluginsTargets()
 */
class ExtensionBPTField extends Extension implements IExtensionBPTField
{
    public function applyFieldPlugins(IBPTransitionField &$field = null): void
    {
        /**
         * @var IFieldPluginTarget[] $fpts
         */
        $fpts = $this->pluginsTargets()->all([
            IFieldPluginTarget::FIELD__TARGET_ID => $field->getId()
        ]);

        foreach ($fpts as $fpt) {
            $plugin = $fpt->getPlugin()->buildClassWithParameters();
            $plugin($field);
        }
    }
}
