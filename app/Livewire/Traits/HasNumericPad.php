<?php

namespace App\Livewire\Traits;

trait HasNumericPad
{
    public $currentInput = 'amount';

    public function setInput($type)
    {
        $this->currentInput = $type;
    }

    public function writeDown($char)
    {
        $val = (string) $this->{$this->currentInput};

        if ($val === '0' && $char !== '.') {
            $val = $char;
        } else {
            if ($char === '.' && str_contains($val, '.')) {
                return;
            }
            $val .= $char;
        }

        $this->{$this->currentInput} = $val;
    }

    public function removeLast()
    {
        $val = (string) $this->{$this->currentInput};
        if (strlen($val) > 1) {
            $this->{$this->currentInput} = substr($val, 0, -1);
            if (str_ends_with($this->{$this->currentInput}, '.')) {
                $this->{$this->currentInput} = substr($this->{$this->currentInput}, 0, -1);
            }
        } else {
            $this->{$this->currentInput} = 0;
        }
    }

    public function clear()
    {
        $this->{$this->currentInput} = 0;
    }
}
