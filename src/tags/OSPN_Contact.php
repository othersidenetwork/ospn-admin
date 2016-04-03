<?php

namespace OSPN\tags;


use OSPN\OSPN_Base;

class OSPN_Contact extends OSPN_Base
{
    /** @var  string $type */
    private $type;
    /** @var  string $value */
    private $value;
    /** @var  string $name */
    private $name;

    function __construct($type, $value, $name)
    {
        $this->type = $type;
        $this->value = $value;
        $this->name = $name;
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

    public function the_name($echo = true) {
        if ($echo) {
            echo $this->name;
        }
        return $this->name;
    }
}