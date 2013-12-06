<?php
/**
 * Class installwidget
 *
 * @author Nikolaev D.
 */
class installwidget extends supercontroller {
    use trait_extension_controller;

    /**
     * @var string
     */
    public  $extension;

    public function  __construct()
    {
        $this->extension = 'installer';
    }

    /**
     * @return mixed|string
     */
    public function display()
    {
        $ini = $this->getLang('install_widget');

        return templator::getTemplate(
            'index',
            $ini,
            '..'.DS.'extensions'.DS.'installer'.DS.'widget_views'.DS.'install'
        );
    }
}