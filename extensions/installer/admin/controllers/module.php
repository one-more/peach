<?php

/**
 * Class modulecontroller
 *
 * @author Nikolaev D.
 */
class modulecontroller extends supercontroller{

    /**
     *
     */
    public function load()
    {
        $this->clear();

        try {
            foreach($_FILES as $el) {
                if($el['type'] == 'application/x-php') {
                    $path = SITE_PATH.'extensions'.DS.'installer'.DS.'auto_install'.DS.$el['name'];
                    move_uploaded_file($el['tmp_name'], $path);
                    $this->install($path);
                }
            }
        }
        catch(Exception $e) {
            //delete all files from folder auto_install
            $this->clear();

            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param $file
     * @throws Exception
     */
    public function install($file)
    {
        if(file_exists($file)) {
            $phar = new Phar($file);
            $ini = parse_ini_file($phar['install.ini'], true);
            $info = $ini['info'];
            if(is_array($info)) {

                $model = installer::getAdminModel('module');

                switch($info['type']) {
                    case 'extension':
                        $data = [
                            'name'          => '',
                            'is_daemon'     => '',
                            'cms_version'   => ''
                        ];
                        $data = array_merge($data, $info);

                        $model->install_extension($data);
                        require $file;
                        break;
                    case 'template':
                        $data = [
                            'name'          => '',
                            'template_type' => '',
                            'cms_version'   => ''
                        ];
                        $data = array_merge($data, $info);

                        $model->install_template($data);
                        require $file;
                        break;
                    case 'editor':
                        $data = [
                            'name'  => ''
                        ];
                        $data = array_merge($data, $info);

                        $model->install_editor($data);
                        require $file;
                        break;
                    case 'update':
                        if(empty($info['for']) || empty($info['version'])) {
                            throw new Exception('wrong ini file');
                        }

                        if(in_array($info['for'], ['system', 'cms'])) {
                            $ver = system::get_version();

                            if($info['version'] > $ver) {
                                require $file;
                            }
                            else {
                                throw new Exception('current version of cms is higher');
                            }
                        }
                        elseif(class_exists($info['for'])) {
                            $class = $info['for'];
                            if(is_callable([$class, 'get_version'])) {
                                $ver = $class::get_version();

                                if($info['version'] > $ver) {
                                    require $file;
                                }
                            }
                            else {
                                throw new Exception('current version of class is higher or
                                method get_version does not exists');
                            }
                        }
                        else {
                            throw new Exception('class does not exists');
                        }
                        break;
                    case 'view':
                        if(class_exists($info['for'])) {
                            require $file;
                        }
                        else {
                            throw new Exception('class does not exists');
                        }
                        break;
                    default:
                        throw new Exception('unknown type');
                        break;
                }
                installer::clear_cache(true);
            }
            else {
                throw new Exception('wrong ini file');
            }
        }
    }

    /**
     *
     */
    public function clear()
    {
        $iterator = new FilesystemIterator(SITE_PATH.'extensions'.DS.'installer'.DS.'auto_install');

        foreach($iterator as $el) {
            unlink($el);
        }
    }
}