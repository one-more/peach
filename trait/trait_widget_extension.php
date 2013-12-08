<?php
/**
 * Class trait_widget_extension
 *
 * @author Nikolaev D.
 */
trait trait_widget_extension {
    /**
     * @return array
     */
    public static function get_widgets()
    {
        $name = get_called_class();

        $result = [];

        $iterator = new FilesystemIterator("../extensions/$name/widgets/");

        foreach($iterator as $file) {
            require_once($file->getFilename());

            $class = preg_split('/\./', $file->getFilename())[0];

            $widget = new $class();

            if($widget instanceof widget_controller_interface) {
                $result[] = $widget->get_info();
            }
        }

        return $result;
    }

    /**
     * @param $class
     */
    public static function get_widget($class) {
        $name = get_called_class();

        require_once("../extensions/$name/widgets/$class.php");

        $class = $class.'widget';

        $class = new $class();

        $defaults = [
            'task'  => 'display'
        ];

        $data = array_merge($defaults, $_REQUEST);

        echo $class->exec($data['task']);
    }
}