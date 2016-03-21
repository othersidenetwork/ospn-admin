<?php
/**
 * Created by IntelliJ IDEA.
 * User: yannick
 * Date: 19.03.16
 * Time: 13:09
 */

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