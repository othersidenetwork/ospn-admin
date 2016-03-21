<?php
/**
 * Created by IntelliJ IDEA.
 * User: yannick
 * Date: 18.03.16
 * Time: 08:27
 */

namespace OSPN;

/**
 * Class OSPN_MenuActions
 * @package OSPN
 */
class OSPN_MenuActions extends OSPN_Base
{
    public function members() {
        global $message;
        $message = "hello, world !";
    }

    public function member_new() {
        global $wpdb, $allusers;
        $allusers = $wpdb->get_results("SELECT ID, display_name FROM $wpdb->users ORDER BY display_name ASC");
    }

}