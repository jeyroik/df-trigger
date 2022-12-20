<?php
namespace df\interfaces\extensions;

use df\interfaces\applications\dispatchers\IAppDispatcher;
use df\interfaces\applications\settings\IAppSetting;

interface IExtensionAppSettings
{
    public const SETTING__DISPATCHER = 'dispatcher';
    public const SETTING__AVATAR = 'avatar';

    public function getSettings(): array;
    public function getSetting(string $name): ?IAppSetting;

    public function getDispatcher(): IAppDispatcher;
}
