<?php
namespace user_site;

/**
 * Class authcontroller
 *
 * @package user_site
 *
 * @author Nikolaev D.
 */
class authcontroller extends \supercontroller {

    /**
     * @return bool
     */
    public function get_id()
    {
        if(!empty($_COOKIE['site_user'])) {
            $cookie = $_COOKIE['site_user'];

            $len    = $cookie[strlen($cookie)-1];

            $id     = substr($cookie, 15, $len);
        }

        if(!empty($_SESSION['site_user'])) {
            $cookie = $_SESSION['site_user'];

            $len    = $cookie[strlen($cookie)-1];
            $id     = substr($cookie, 15, $len);
        }

        if($id) {
            $obj = \user::get($id);

            if($obj) {
                return $id;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function is_auth()
    {
        if(!empty($_COOKIE['site_user'])) {
            $cookie = $_COOKIE['site_user'];

            $len    = $cookie[strlen($cookie)-1];
            $id     = substr($cookie, 15, $len);

            $obj    = \user::get($id);

            if($obj) {
                $file   = \user::$path.'site'.DS.$cookie.DS.$_COOKIE['PHPSESSID'];

                if(!file_exists($file)) {

                    $token  = crypt(getenv('REMOTE_ADDR').$id.$_COOKIE['PHPSESSID']);

                    $arr = [
                        'token' => $token
                    ];

                    $iterator   =
                        new \FilesystemIterator(\user::$path.'site'.DS.$cookie);

                    foreach($iterator as $el) {
                        unlink($el);
                    }

                    file_put_contents($file, json_encode($arr));

                    $_SESSION['site_token'] = $token;
                }

                $file   =
                    \user::$path.'site'.DS.'session_files'.DS.$_COOKIE['PHPSESSID'];

                if(!file_exists($file)) {
                    $arr = [
                        'my_ip' => getenv('REMOTE_ADDR')
                    ];

                    file_put_contents($file, json_encode($arr));
                }

                $iterator   =
                    new \FilesystemIterator(
                        \user::$path.'site'.DS.'session_files'
                    );

                foreach($iterator as $el) {
                    if(time() > filemtime($el)+3600*72) {
                        unlink($el);
                    }
                }

                return true;
            }
            else {
                return false;
            }
        }

        if(!empty($_SESSION['site_user'])) {
            $cookie = $_SESSION['site_user'];

            $len    = $cookie[strlen($cookie)-1];
            $id     = substr($cookie, 15, $len);

            $obj    = \user::get($id);

            if($obj) {

                $iterator   =
                    new \FilesystemIterator(\user::$path.'site'.DS.'session_files');

                foreach($iterator as $el) {
                    if(time() > filemtime($el)+3600*72) {
                        unlink($el);
                    }
                }

                return true;
            }
            else {
                return false;
            }
        }
    }

    /**
     *
     */
    public function auth()
    {
        $model  = \user::get_site_model('auth');
        $lang   = \user::read_lang('auth');

        $errors = $model->auth($_POST);

        if(is_array($errors)) {
            return ['error' => $errors];
        }
        elseif($errors) {
            $rem = !empty($_POST['remember_me']) ? 1 : 0;

            $token  = crypt(getenv('REMOTE_ADDR').$errors.$_COOKIE['PHPSESSID']);

            if($rem == 1) {
                $cookie  = sha1($errors.time().$_COOKIE['PHPSESSID'].rand(0, 10000));
                $len     = strlen($errors);
                $cookie  = substr($cookie, 0, 15).$errors.substr($cookie, 15).$len;

                setcookie('site_user', $cookie, 0);

                $dir    = \user::$path.'site'.DS.$cookie;

                mkdir($dir);

                $file   = $dir.DS.$_COOKIE['PHPSESSID'];

                $arr    = [
                    'token' => $token
                ];

                file_put_contents($file, json_encode($arr));

                $_SESSION['site_toke']  = $token;

                $file   =
                    \user::$path.'site'.DS.'session_files'.DS.$_COOKIE['PHPSESSID'];

                $arr = [
                    'my_ip' => getenv('REMOTE_ADDR')
                ];

                file_put_contents($file, json_encode($arr));
            }
            else {
                $dir    = \user::$path.'site'.DS.'session_files';

                if(!file_exists($dir)) {
                    mkdir($dir);
                }

                $file   = $dir.DS.$_COOKIE['PHPSESSID'];
                $arr    = [
                    'my_ip' => getenv('REMOTE_ADDR'),
                    'token' => $token
                ];

                file_put_contents($file, json_encode($arr));

                $cookie  = sha1($errors.time().$_COOKIE['PHPSESSID'].rand(0, 10000));
                $len     = strlen($errors);
                $cookie  = substr($cookie, 0, 15).$errors.substr($cookie, 15).$len;

                $_SESSION['site_user']  = $cookie;
                $_SESSION['site_token'] = $token;
            }

            \comet::add_message(
                [
                    'task'      => 'delegate',
                    'object'    => 'App',
                    'method'    => 'reload',
                    'params'    => []
                ],
                'me_site'
            );
        }
        else {
            \comet::add_message(
                [
                    'task'      => 'delegate',
                    'object'    => 'App',
                    'method'    => 'showNoty',
                    'params'    => [$lang['wrong'], 'error']
                ],
                getenv('REMOTE_ADDR')
            );
        }
    }

    /**
     *
     */
    public function leave()
    {
        if(!empty($_COOKIE['site_user'])) {

            $cookie = $_COOKIE['site_user'];

            \helper::remDir(\user::$path.'site'.DS.$cookie);

            $file   =
                \user::$path.'site'.DS.'session_files'.DS.$_COOKIE['PHPSESSID'];

            unlink($file);

            setcookie('site_user', '', -1);
        }

        if(!empty($_SESSION['site_user'])) {

            unset($_SESSION['site_user']);

            $file   = \user::$path.'site'.DS.'session_files'.DS.
                $_COOKIE['PHPSESSID'];

            unlink($file);
        }

        if(!empty($_SESSION['site_token'])) {
            unset($_SESSION['site_token']);
        }

        if(!empty($_REQUEST['ajax'])) {
            \comet::add_message(
                [
                    'task'      => 'delegate',
                    'object'    => 'App',
                    'method'    => 'reload',
                    'params'    => []
                ],
                getenv('REMOTE_ADDR')
            );
        }
    }
}