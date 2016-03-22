<?php

namespace OSPN;


class OSPN_Base
{
    /**
     * @param $message string
     */
    protected function log($message) {
        error_log($message, 4);
    }

}