<?php
class core {
    public static function initialise() {
        session_start();

        if(!file_exists('config.txt')) {
            echo '<script>location = "/install/";</script>';
        }
        elseif(is_dir('./install')) {
            //helper::remDir('./install');
        }
    }
}