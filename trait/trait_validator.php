<?php
/**
 * Class trait_validator - validates form fields
 */
trait trait_validator {

	public static $refrence = [
		'email'			=> 'неверный формат email',
		'not_empty'		=> 'поле не может быть пустым',
	];

	public static function check(&$data, $validator = []) {
		$error = [];

		foreach($validator as $k=>$v)
		{
			$func = 'valid_'.$v;

			if($str = static::$func($data[$k])) {
				$error[$k] =	$str;
			}
		}

		return $error;
	}

	public static function valid_email($email) {
		if (preg_match('/^[a-z0-9_.-]{1,100}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|inc|name|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is', $email))
		{
			return false;
		}
		else {
			return static::$refrence['email'];
		}
	}

	public static function valid_not_empty($v) {
		if(empty($v)) {
			return static::$refrence['not_empty'];
		}
		else {
			return false;
		}
	}
}