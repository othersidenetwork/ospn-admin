<?php

namespace OSPN\tags;


use OSPN\OSPN_Base;

class OSPN_Contact extends OSPN_Base
{
    /** @var  string $type */
    private $type;
    /** @var  string $value */
    private $value;

    function __construct($type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    public function the_url($echo = true) {
        if ($echo) {
            echo $this->value;
        }
        return $this->value;
    }

    public function the_type($echo = true) {
        if ($echo) {
            echo $this->type;
        }
        return $this->type;
    }
}