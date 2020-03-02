<?php

namespace App\Other;

class DataTemplate
{
    public $status = 'success';

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}
