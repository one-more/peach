<?php
namespace user_admin;

/**
 * Class userscontroller
 * @package user_admin
 * @author Nikolaev D.
 */
class userscontroller extends \supercontroller {

    use \trait_extension_controller;

    /**
     *
     */
    public function __construct()
    {
        $this->_cache_path = \user::$path.'admin'.DS.'cache'.DS;
        $this->extension = 'user';

        if(!file_exists(SITE_PATH.'www'.DS.'media'.DS.'users_avatars')) {
            mkdir(SITE_PATH.'www'.DS.'media'.DS.'users_avatars');
        }
    }

    /**
     * @return mixed|string
     */
    public function display() {

        $params = \factory::get_reference('privileges');

        $model = \user::get_admin_model('users');

        $all = $model->get_all('users');

        $trs = '';
        $tr = '';

        foreach($all as $el) {
            $tr = '';

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
                        'class' => 'icon-edit user-edit-btn cursor-pointer float-left',
                        'data-params' => $el['id']
                    ]);
                $i .= \dom::create_element(
                    '<i>',
                    [
                        'class'         => 'icon-trash user-delete-btn cursor-pointer',
                        'data-params'   => $el['id']
                    ]
                );

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
            $model = \user::get_admin_model('user');

            if($this->get_cache_view('user_info_'.$id)) {
                $arr = json_decode($this->get_cache_view('user_info_'.$id), true);
            }
            else {
                $arr = $model->get($id, true);

                $this->set_cache_view('user_info_'.$id, json_encode($arr));
            }

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
            else {

                $ref = \user::read_lang('references')['user_created'];

                \comet::add_message(
                    [
                        'task'      => 'delegate',
                        'object'    => 'App',
                        'method'    => 'closeModal',
                        'params'    => []
                    ]
                );

                \comet::add_message(
                    [
                        'task'      => 'delegate',
                        'object'    => 'UserView',
                        'method'    => 'update_users_table',
                        'params'    => []
                    ]
                );

                \comet::add_message(
                    [
                        'task'      => 'delegate',
                        'object'    => 'App',
                        'method'    => 'showNoty',
                        'params'    => [$ref, 'success']
                    ]
                );
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

            $params['id'] = '';

            $params = array_merge($params, $refs);

            $password_label = \dom::create_element(
                'label',
                [
                    'class'     => 'control-label',
                    'for'       => 'input_password',
                    'text'      => $params['PASSWORD_LABEL'].' '.\dom::create_element('sup',['text'=>'*'])
                ]
            );

            $params['pswd_label'] = $password_label;

            return \templator::getTemplate(
                'edit',
                $params,
                \user::$path.'admin'.DS.'views'.DS.'users'
            );
        }
        else {
            return \templator::get_warning(\user::read_lang('create_edit_page')['HAVE_NO_RIGHTS']);
        }
    }

    /**
     * @param $id
     * @return string
     */
    public function edit($id)
    {
        if($id) {

            $model = \user::get_admin_model('user');

            if($this->get_cache_view('user_info_'.$id)) {
                $user = json_decode($this->get_cache_view('user_info_'.$id), true);
            }
            else {
                $user = $model->get($id, true);

                $this->set_cache_view('user_info_'.$id, json_encode($user));
            }

            $privs = \factory::get_reference('privileges');

            $refs = \factory::get_reference('user');

            $opt = '';

            foreach($privs as $k=>$v) {
                if($user['user']['credentials'] === $k)
                    $opt = \dom::create_element(
                        'option',
                        [
                            'value'     => $k,
                            'text'      => $v,
                            'selected'  => ''
                        ]
                    );
                else
                    $opt .= \dom::create_element(
                        'option',
                        [
                            'value' => $k,
                            'text'  => $v
                        ]
                    );
            }

            $select = \dom::create_element(
                'select',
                [
                    'name'  => 'credentials',
                    'text'  => $opt,
                    'class' => 'align-center'
                ]
            );

            $params = array_merge($user['user'], $user['info']);

            $params['full_name_val']    = $params['full_name'];
            $params['phone_val']        = $params['phone'];
            $params['site_val']         = $params['site'];

            $lbls = \user::read_lang('create_edit_page');

            $params = array_merge($params, $lbls);

            $params['BTN_LABEL'] = $params['SAVE_BTN_LABEL'];

            $params['action'] = 'index.php?class=user&controller=users&task=edit';

            $params['credentials'] = $select;

            $params['id'] = $id;

            $params = array_merge($params, $refs);

            foreach($params as $k=>$v) {
                if($v == '-') {
                    $params[$k] = '';
                }
            }

            $params['password'] = '';

            $password_label = \dom::create_element(
                'label',
                [
                    'class'     => 'control-label',
                    'for'       => 'input_password',
                    'text'      => $params['NEW_PASSWORD_LABEL']
                ]
            );

            $params['pswd_label'] = $password_label;

            return \templator::getTemplate(
                'edit',
                $params,
                \user::$path.'admin'.DS.'views'.DS.'users'
            );
        }
        elseif($_POST) {
            $model = \user::get_admin_model('user');

            $answer = $model->edit(
                $user   = [
                    'login'         => $_POST['login'],
                    'password'      => $_POST['password'],
                    'credentials'   => $_POST['credentials']
                ],
                $info   = [
                    'full_name'         =>  $_POST['full_name'],
                    'email'             =>  $_POST['email'],
                    'phone'             =>  $_POST['phone'],
                    'icq'               =>  $_POST['icq'],
                    'skype'             =>  $_POST['skype'],
                    'site'              =>  $_POST['site'],
                    'facebook'          =>  $_POST['facebook'],
                    'twitter'           =>  $_POST['twitter'],
                    'avatar'            =>  $_POST['avatar']
                ],
                $_POST['id']
            );

            if(is_array($answer)) {
                return ['error' => $answer];
            }

            $ref = \user::read_lang('references')['user_edited'];

            \comet::add_message(
                [
                    'task'      => 'delegate',
                    'object'    => 'App',
                    'method'    => 'closeModal',
                    'params'    => []
                ]
            );

            \comet::add_message(
                [
                    'task'      => 'delegate',
                    'object'    => 'App',
                    'method'    => 'showNoty',
                    'params'    => ['0' => $ref, '1' => 'success']
                ]
            );

            \comet::add_message(
                [
                    'task'      => 'delegate',
                    'object'    => 'UserView',
                    'method'    => 'update_users_table',
                    'params'    => []
                ]
            );

            \comet::add_message(
                [
                    'task'      => 'delegate',
                    'object'    => 'UserWidgetView',
                    'method'    => 'update',
                    'params'    => []
                ]
            );

            $this->delete_cache_view('user_info_'.$_POST['id']);

            $this->manage_avatars();
        }
        else {
            return \templator::get_warning('no data');
        }
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        if($id) {

            $model = \user::get_admin_model('user');

            $model->delete($id);

            $this->delete_cache_view('user_info_'.$id);

            \comet::add_message(
                [
                    'task'      => 'delegate',
                    'object'    => 'UserView',
                    'method'    => 'update_users_table',
                    'params'    => []
                ]
            );

            $ref = \user::read_lang('references')['user_deleted'];

            \comet::add_message(
                [
                    'task'      => 'delegate',
                    'object'    => 'App',
                    'method'    => 'showNoty',
                    'params'    => [$ref, 'success']
                ]
            );

            $this->manage_avatars();
        }
    }

    /**
     * removes avatars that are no longer used
     */
    public function manage_avatars()
    {
        $model = \user::get_admin_model('user');

        $all = $model->get_all('user_info');

        $avatars = [];

        foreach($all as $el) {
            $avatars[] = basename($el['avatar']);
        }

        $iterator = new \FilesystemIterator(SITE_PATH.'www'.DS.'media'.DS.'users_avatars');

        foreach($iterator as $el) {
            if(!in_array($el->getFilename(), $avatars)) {
                unlink($el);
            }
        }
    }
}