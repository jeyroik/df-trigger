<?php
namespace df\interfaces\applications\dispatchers;

use df\interfaces\applications\IApplication;
use df\interfaces\applications\IApplicationEvent;
use extas\interfaces\IItem;
use extas\interfaces\routes\IRouteDispatcher;

interface IAppDispatcher extends IItem
{
    public const SUBJECT = 'df.app.dispatcher';

    public const FIELD__APPLICATION = 'app';

    public function getEvent(string $stateName, IRouteDispatcher $dispatcher): IApplicationEvent;
    public function trigger(string $stateName, array $body): array;

    public function getApplication(): ?IApplication;
    public function setApplication(IApplication $app): self;
}
