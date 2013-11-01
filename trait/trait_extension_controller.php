<?php
/**
 * Class trait_extension_controller
 */
trait trait_extension_controller {
	/**
	 * @param $name
	 * @param $data
	 */
	public function set_cache_view($name, $data) {
		$path = $this->_cahe_path."/$name";

		file_put_contents($path, $data);
	}

	/**
	 * @param $name
	 * @return bool|string
	 */
	public function get_cache_view($name) {
		$path = $this->_cahe_path."/$name";

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
		$path = $this->_cahe_path."/$name";

		if(file_exists($path)) {
			unlink($path);

			return true;
		}

		return false;
	}
}