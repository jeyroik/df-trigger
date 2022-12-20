<?php
namespace df\components\extensions;

use df\interfaces\applications\dispatchers\IAppDispatcher;
use df\interfaces\applications\IApplication;
use df\interfaces\applications\settings\IAppSetting;
use df\interfaces\extensions\IExtensionAppSettings;
use extas\components\exceptions\MissedOrUnknown;
use extas\components\extensions\Extension;
use extas\interfaces\repositories\IRepository;

/**
 * @method IRepository appSettings()
 */
class ExtensionAppSettings extends Extension implements IExtensionAppSettings
{
    public function getSettings(IApplication $app = null): array
    {
        return $this->appSettings()->all([IAppSetting::FIELD__APPLICATION_ID => $app->getId()]);
    }

    public function getSetting(string $name, IApplication $app = null): ?IAppSetting
    {
        return $this->appSettings()->one([
            IAppSetting::FIELD__APPLICATION_ID => $app->getId(),
            IAppSetting::FIELD__NAME => $name
        ]);
    }

    public function getDispatcher(IApplication $app = null): IAppDispatcher
    {
        $setting = $this->getSetting(static::SETTING__DISPATCHER, $app);

        if (!$setting) {
            throw new MissedOrUnknown('app "'.static::SETTING__DISPATCHER.'" setting');
        }

        $className = $setting->getValue();

        return new $className([
            IAppDispatcher::FIELD__APPLICATION => $app
        ]);
    }
}
