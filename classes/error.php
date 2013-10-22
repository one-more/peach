<?php
class error {
    public static function log($msg) {
        file_put_contents('../error.log',date('j.m.Y H:i:s').' - '.$msg."\r\n", FILE_APPEND);
    }
}