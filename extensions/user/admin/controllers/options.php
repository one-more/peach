<?php
namespace user_admin;

/**
 * Class optionscontroller
 *
 * @package user_admin
 *
 * @author Nikolaev D.
 */
class optionscontroller extends \supercontroller {
    use \trait_validator;

    /**
     * @var array
     */
    static $reference = [];

    /**
     * @return mixed|string
     */
    public function display()
    {
        $params = \user::read_lang('options_page');

        $params['exit_time'] = \user::read_params('options', 'exit_time', 15);

        return \templator::getTemplate(
            'index',
            $params,
            \user::$path.'admin'.DS.'views'.DS.'options'
        );
    }

    /**
     * @param $section
     * @return array
     */
    public function save($section)
    {
        if($section) {
            static::$reference = \factory::get_reference('reference');

            switch($section) {
                case 'options':
                    $errors = static::check($_POST, [
                        'exit_time' => ['not_empty', 'positive_number']
                    ]);

                    if($errors) {
                        return ['error' => $errors];
                    }

                    $data = [
                        'exit_time' => 15
                    ];

                    $data = array_merge($data, $_POST);
                    break;
            }

            \user::write_params($section, $data);

            \comet::add_message(
                [
                    'task'      => 'delegate',
                    'object'    => 'UserView',
                    'method'    => 'update_options_page',
                    'params'    => []
                ]
            );
        }
        else {
            $refs = \factory::get_reference('errors');

            \comet::add_message(
                [
                    'task'      => 'delegate',
                    'object'    => 'App',
                    'method'    => 'showNoty',
                    'params'    => [$refs['request_error'], 'error']
                ]
            );
        }
    }
}