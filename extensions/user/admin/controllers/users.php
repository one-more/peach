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
                    'text'  => $el['login'],
                    'class' => 'cursor-pointer view-user-btn external'
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
}