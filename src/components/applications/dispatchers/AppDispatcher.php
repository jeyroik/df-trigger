<?php
namespace df\components\applications\dispatchers;

use df\interfaces\applications\dispatchers\IAppDispatcher;
use df\interfaces\applications\IApplication;
use extas\components\Item;

abstract class AppDispatcher extends Item implements IAppDispatcher
{
    public function getApplication(): ?IApplication
    {
        return $this->config[static::FIELD__APPLICATION] ?? null;
    }

    public function setApplication(IApplication $app): self
    {
        $this->config[static::FIELD__APPLICATION] = $app;

        return $this;
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
