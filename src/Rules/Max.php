<?php
namespace Progsmile\Validator\Rules;

class Max extends BaseRule
{
    private $isNumeric = false;

    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        if ($this->hasRule('numeric') !== false) {

            $this->isNumeric = true;

            return $this->params[1] <= $this->params[2];
        }

        return is_string($this->params[1]) && strlen($this->params[1]) <= $this->params[2];
    }
}
