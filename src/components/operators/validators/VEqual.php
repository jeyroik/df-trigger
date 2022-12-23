<?php
namespace df\components\operators\validators;

class VEqual extends Validator
{
    public function isValid(): bool
    {
        return $this->getAppEventValue() == $this->getValue();
    }
}
