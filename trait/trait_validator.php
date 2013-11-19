<?php
/**
 * Class trait_validator - validates form fields
 */
trait trait_validator {
    /**
     * @param $data
     * @param array $validator
     * @return array
     */
    public static function check(&$data, $validator = []) {
		$error = [];

		foreach($validator as $k=>$v)
		{
			if(is_array($v)) {
                $tmp_array = [];

                foreach($v as $el) {
                    $cur_validator = 'valid_'.$el;

                    if($str = static::$cur_validator($data[$k])) {
                        $tmp_array[] = $str;
                    }
                }

                if($str = reset($tmp_array))
                {
                    $error[$k] = $str;
                }
            }
            else{
                $func = 'valid_'.$v;

                if($str = static::$func($data[$k])) {
                    $error[$k] =	$str;
                }
            }
		}

		return $error;
	}

    /**
     * @param $v
     * @return bool
     */
    public static function valid_email($v) {
		if (filter_var($v, FILTER_VALIDATE_EMAIL))
		{
			return false;
		}
		else {
			return static::$reference['email'];
		}
	}

    /**
     * @param $v
     * @return bool
     */
    public static function valid_not_empty($v) {
		if(empty($v)) {
			return static::$reference['not_empty'];
		}
		else {
			return false;
		}
	}

    /**
     * @param $v
     * @return bool
     */
    public static function valid_password($v)
    {
        if(strlen($v) < 5) {
            return static::$reference['password'];
        }
        else {
            return false;
        }
    }
}