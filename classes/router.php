<?php
class router {
    public static function route() {
        $addr = $_SERVER['REQUEST_URI'];

        $addr = preg_split('/\//', $addr);

        print_r($addr);
    }
}