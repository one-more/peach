<?php
namespace simple_template_admin;

/**
 * Class optionscontroller
 *
 * @package simple_template_admin
 *
 * @author Nikolaev D.
 */
class optionscontroller extends \supercontroller {

    use \trait_validator;

    static $reference;

    /**
     * @return string
     */
    public function display()
    {
        $params = \simple_template::get_lang('options_page');
        $params = array_merge($params, \simple_template::read_params('options'));

        return \templator::getTemplate(
            'index',
            $params,
            \simple_template::$path.'admin'.DS.'views'.DS.'options'
        );
    }

    public function save()
    {
        if($_POST) {
            $defaults = [
                'logo_text'     => '',
                'footer_text'   => ''
            ];

            static::$reference = \factory::get_reference('reference');

            $data = array_merge($defaults, $_POST);

            $errors = static::check($data, [
               'logo_text'      => 'not_empty',
                'footer_text'   => 'not_empty'
            ]);

            if($errors) {
                return ['error' => $errors];
            }
            else {
                $ref = \factory::get_reference('success')['options_saved'];

                \simple_template::write_params('options', $data);

                \comet::add_message([
                   'task'       => 'delegate',
                    'object'    => 'App',
                    'method'    => 'showNoty',
                    'params'    => [$ref, 'success']
                ]);

                \comet::add_message([
                   'task'       => 'delegate',
                    'object'    => 'App',
                    'method'    => 'updatePage',
                    'params'    => []
                ]);
            }
        }
    }
}