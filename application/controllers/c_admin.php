<?php
/**
 * User: Elio
 * Date: 23.12.12
 *
 */
class C_Admin extends C_Base
{
    function index()
    {
        if (!empty($_POST['passwd'])) {
            $phrase=explode('_',trim(strip_tags($_POST['passwd'])));
            $public = md5($phrase[0]) . $phrase[1];
            $private = $this->settings['passwd'] . date('dG',time());
            if ($public == $private) {
                $_SESSION['admin_key'] = $this->settings['admin_key'];
            }
        }
        if (empty($_SESSION['admin_key'])) {
            Registry::$display->disp('admin/signin');
        } elseif ($_SESSION['admin_key'] == $this->settings['admin_key']) {
            Registry::$display->disp('admin/index');
        }
    }
}
