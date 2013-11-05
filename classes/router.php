<?php
class router {
    public static function route() {

		$extension = preg_split('/\//', $_SERVER['REQUEST_URI'])[1];

		if($extension == 'admin') {
			site::$_mode = $extension;
		}


		if(!empty($_REQUEST['ajax'])) {
            $defaults = [
                'type'          => 'extension',
                'extension'     => '',
            ];

            $data = array_merge($defaults, $_REQUEST);

            switch($data['type']) {
                case 'extension':
                    $extension = $data['extension'];

                    $extension::start();
                    break;
            }

            exit;
        }

        if(in_array($extension, ['admin', 'develop'])) {
            $extension::start();

            exit;
        }
    }
}