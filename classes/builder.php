<?php
/**
 * assembles js and css files into one
 *
 * Class builder
 *
 * @author Nikolaev D.
 */
class builder {

    /**
     *
     */
    public static function initialize()
    {
        if(!is_dir(SITE_PATH.'www'.DS.'js'.DS.'builder')) {
            mkdir(SITE_PATH.'www'.DS.'js'.DS.'builder');
            return;
        }
        if(!is_dir(SITE_PATH.'www'.DS.'css'.DS.'builder')) {
            mkdir(SITE_PATH.'www'.DS.'css'.DS.'builder');
            return;
        }

        $js_iterator    = new FilesystemIterator(SITE_PATH.'www'.DS.'js'.DS.'builder');
        $css_iterator   = new FilesystemIterator(SITE_PATH.'www'.DS.'css'.DS.'builder');

        foreach($js_iterator as $el) {
            if(time() > filemtime($el) + 3600*24*7) {
                unlink($el);
            }
        }

        foreach($css_iterator as $el) {
            if(time() > filemtime($el) + 3600*24*7) {
                unlink($el);
            }
        }
    }

    /**
     * @param $name
     * @param $arr
     * @param bool $tag - if true, returns tag else path
     * @return string
     * @throws Exception
     */
    public static function build($name, $arr, $tag = true)
    {
        $output = '';

        $patterns = [
            '/\s+\/\/.*/',
            '/\s/'
        ];

        $replacements = [
            '',
            ' '
        ];

        $ext = preg_split('/\./', $name)[1];

        foreach($arr as $el) {

            if(preg_match('/src="(.*)"/', $el, $total)) {
                $path = $total[1];
            }
            elseif(preg_match('/href="(.*)"/', $el, $total)) {
                $path = $total[1];
            }
            else {
                throw new Exception('no js or css file in array in builder::build');
            }

            //todo - make minimization

            $tmp = file('.'.$path);

            foreach($tmp as $el) {
                $el = preg_replace('/\.\.\//', '/', $el);

                $output .= $el."\r\n";
            }

        }

        file_put_contents($ext.DS.'builder'.DS.$name, $output);

        if($ext == 'js') {
            $script = dom::create_element('<script>', ['src'=>'/js/builder/'.$name]);

            return $tag ? $script : "/js/builder/$name";
        }
        else {
            $link = dom::create_element('<link>',
                ['rel'=>'stylesheet',
                    'href'=>'/css/builder/'.$name]);

            return $tag ? $link  : "/css/builder/$name";
        }
    }
}