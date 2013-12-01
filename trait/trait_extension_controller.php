<?php
/**
 * Class trait_extension_controller
 *
 * @author Nikolaev D
 */
trait trait_extension_controller {
	/**
	 * @param $name
	 * @param $data
	 */
	public function set_cache_view($name, $data) {
		$path = $this->_cache_path."$name";

		file_put_contents($path, $data);
	}

	/**
	 * @param $name
	 * @return bool|string
	 */
	public function get_cache_view($name) {
		$path = $this->_cache_path."$name";

		if(file_exists($path)) {
			return file_get_contents($path);
		}

		return false;
	}

	/**
	 * @param $name
	 * @return bool
	 */
	public function delete_cache_view($name) {
		$path = $this->_cache_path."$name";

		if(file_exists($path)) {
			unlink($path);

			return true;
		}

		return false;
	}

	/**
	 * returns lang vars for requested page
	 *
	 * @param $page
	 * @param string $default
	 * @return null
	 */
	public function getLang($page, $default = 'en-EN') {
		$lang = site::getLang();

		$path = '..'.DS.'lang'.DS.$this->extension.DS.core::$mode.DS.$lang.'.ini';
		$path2 = '..'.DS.'lang'.DS.$this->extension.DS.core::$mode.DS.$default.'.ini';

		if(file_exists($path)) {
			$ini = factory::getIniServer($path);

			$return = $ini->readSection($page);

			return $return;
		}
		elseif(file_exists($path2)) {
			$ini = factory::getIniServer($path2);

			$return = $ini->readSection($page);

			return $return;
		}
		else {
			return null;
		}
	}
}