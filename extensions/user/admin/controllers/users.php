<?php
namespace user_admin;

/**
 * Class userscontroller
 * @package user_admin
 * @author Nikolaev D.
 */
class userscontroller extends \supercontroller {

    public function display() {

        $params = \factory::get_reference('privileges');

        $model = \user::get_admin_model('users');

        $all = $model->get_all('users');

        $trs = '';
        $tr = '';

        foreach($all as $el) {
            $td = \dom::create_element('td', ['text' => $el['id']]);
            $tr .= $td;

            $a = \dom::create_element(
                'a',
                [
                    'text'          => $el['login'],
                    'class'         => 'cursor-pointer view-user-btn external',
                    'data-params'   => $el['id']
                ]);
            $td = \dom::create_element('td', ['text' => $a]);
            $tr .= $td;

            $td = \dom::create_element('td', ['text' => $params[$el['credentials']]]);
            $tr .= $td;

            if(\user::is_super_admin()) {
                $i = \dom::create_element(
                    'i',
                    [
                        'class' => 'icon-edit user-edit-btn cursor-pointer',
                        'data-params' => $el['id']
                    ]);

                $td = \dom::create_element('td', ['text' => $i]);
                $tr .= $td;
            }
            else {
                $td = \dom::create_element('td', []);
                $tr .= $td;
            }

            $trs .= \dom::create_element('tr', ['text' => $tr]);
        }

        $params['trs'] = $trs;

        return \templator::getTemplate(
            'index',
            $params,
            \user::$path.'admin'.DS.'views'.DS.'users'
        );
    }

    /**
     * @param $id
     * @return string
     */
    public function view($id) {
        if(is_numeric($id)) {
            $controller = \user::get_admin_controller('user');

            $arr = $controller->get($id);

            $params = $arr['user'];

            $params['privileges'] =
                \factory::get_reference('privileges')[$params['credentials']];

            $params['avatar'] = $arr['info']['avatar'];

            unset($arr['info']['id']);
            unset($arr['info']['user']);
            unset($arr['info']['avatar']);

            $str = '';

            $refs = \factory::get_reference('user');

            foreach($arr['info'] as $k=>$v) {
                if(!array_key_exists($k, $refs)) {
                    $refs[$k] = $k;
                }
            }

            foreach($arr['info'] as $k=>$v) {
                $str .= \dom::create_element(
                    '<p>',
                    ['text' => "$refs[$k]: $v"]
                );
            }

            $params['info'] = $str;

            return \templator::getTemplate(
                'view',
                $params,
                \user::$path.'admin'.DS.'views'.DS.'users'
            );
        }
        else {
            return "invalid id - $id";
        }
    }

    public function create() {
        if($_POST) {
            $model = \user::get_admin_model('user');

            $answer = $model->create(
                $user   = [
                'login'         => $_POST['login'],
                'password'      => $_POST['password'],
                'credentials'   => $_POST['credentials']
                ],
                $info   = [
                   'full_name'  =>  $_POST['full_name'],
                    'email'     =>  $_POST['email'],
                    'phone'     =>  $_POST['phone'],
                    'icq'       =>  $_POST['icq'],
                    'skype'     =>  $_POST['skype'],
                    'site'      =>  $_POST['site'],
                    'facebook'  =>  $_POST['facebook'],
                    'twitter'   =>  $_POST['twitter'],
                    'avatar'    =>  $_POST['avatar']
                ]
            );

            if(is_array($answer)) {
                return ['error' => $answer];
            }
        }
        elseif(\user::is_super_admin()) {

            $privs = \factory::get_reference('privileges');

            $refs = \factory::get_reference('user');

            $opt = '';

            foreach($privs as $k=>$v) {
                $opt .= \dom::create_element(
                    '<option>',
                    ['value' => $k, 'text' => $v]
                );
            }

            $select = \dom::create_element(
                '<select>',
                [
                    'name'  => 'credentials',
                    'text'  => $opt,
                    'class' => 'align-center'
                ]
            );

            $params = [
                'avatar'        => \user::read_params('user', 'default_avatar', DS.'media'.DS.'images'.DS.'noavatar.gif'),
                'login'         => '',
                'password'      => '',
                'credentials'   => $select,
                'full_name_val' => '',
                'email'         => '',
                'phone_val'     => '',
                'icq'           => '',
                'skype'         => '',
                'site_val'      => '',
                'facebook'      => '',
                'twitter'       => ''
            ];

            $lbls = \user::read_lang('create_edit_page');

            $params = array_merge($params, $lbls);

            $params['BTN_LABEL'] = $params['CREATE_BTN_LABEL'];

            $params['action'] = 'index.php?class=user&controller=users&task=create';

            $params = array_merge($params, $refs);

            return \templator::getTemplate(
                'edit',
                $params,
                \user::$path.'admin'.DS.'views'.DS.'users'
            );
        }
        else {
            return \user::read_lang('create_edit_page')['HAVE_NO_RIGHTS'];
        }
    }
}