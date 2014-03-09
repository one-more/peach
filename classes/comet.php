<?php

/**
 * Class comet - is used to add messages to comet daemon
 *
 * @author Nikolaev D
 */
class comet {
    /**
     * @param $msg
     * @param string $type
     */
    public static function add_message($msg, $type = 'me_admin')
    {
        $ini = factory::getIniServer(SITE_PATH.'resources'.DS.'comet.ini');
        $msg = base64_encode(json_encode($msg));
        $ip = user::get_ip();
        $ip = preg_replace('/\./', '_', $ip);

        switch($type) {
            case 'me_admin':
                $arr = $ini->readSection('me_admin_'.$ip);
                $arr[] = $msg;
                $ini->writeSection('me_admin_'.$ip, $arr);
                break;
            case 'me_site':
                $arr = $ini->readSection('me_site_'.$ip);
                $arr[] = $msg;
                $ini->writeSection('me_site_'.$ip, $arr);
                break;
            case 'site_users':
                $arr = $ini->get_all();
                foreach($arr as $k=>$v) {
                    if(!preg_match('/.*_admin/', $k)) {
                        $arr[$k][] = $msg;
                    }
                }
                $ini->write_all($arr);
                break;
            default:
                $type = preg_replace('/\./', '_', $type);
                $mode = core::$mode;
                $type .= '_'.$mode;
                $arr = $ini->readSection($type);
                $arr[] = $msg;
                $ini->writeSection($type, $arr);
                break;
        }
        $ini->updateFile();
    }

    /**
     * @param $ip
     * @param $mode
     * @return mixed
     */
    public static function get_array($ip, $mode)
    {
        $my_ip = user::get_ip();
        $preg_ip = preg_replace('/\./', '_', $my_ip);

        $ini = factory::getIniServer(SITE_PATH.'resources'.DS.'comet.ini');

        if($my_ip == $ip) {
            if($mode == 'admin') {
                $arr = $ini->readSection('me_admin_'.$preg_ip);
            }
            else {
                $arr = $ini->readSection('me_site_'.$preg_ip);
            }
        }
        else {
            $ip = preg_replace('/\./', '_', $ip);

            $arr =  $ini->readSection($ip."_{$mode}");
        }

        $ret = [];
        foreach($arr as $el) {
            $ret[] = json_decode(base64_decode($el), true);
        }

        return $ret;
    }

    /**
     * @param $ip
     * @param $mode
     */
    public static function clear_array($ip, $mode)
    {
        $my_ip = user::get_ip();
        $ini = factory::getIniServer(SITE_PATH.'resources'.DS.'comet.ini');
        $preg_ip = preg_replace('/\./', '_', $my_ip);

        if($my_ip == $ip) {
            if($mode == 'admin') {
                $ini->writeSection('me_admin_'.$preg_ip, []);
            }
            else {
                $ini->writeSection('me_site_'.$preg_ip, []);
            }
        }
        else {
            $ip = preg_replace('/\./', '_', $ip);

            $ini->writeSection($ip."_{$mode}", []);
        }
        $ini->updateFile();
    }
}