<?php
namespace df\components\operators\validators;

class VEqInt extends Validator
{
    public function isValid(): bool
    {
        $ev = (int) $this->getAppEventValue();
        $v = (int) $this->getValue();

        return $ev == $v;
    }
}
