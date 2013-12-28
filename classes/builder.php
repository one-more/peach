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
     * @param $name
     * @param $arr
     * @return string
     * @throws Exception
     */
    public static function build($name, $arr)
    {
        $output = '';

        $patterns = [
            "/\s/"
        ];

        $replacements = [
            ""
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
            //$tmp = file_get_contents('.'.$path);

            //$output .= preg_replace($patterns, $replacements, $tmp);

            $tmp = file('.'.$path);

            foreach($tmp as $el) {
                $el = preg_replace('/\.\.\//', '/', $el);

                $output .= $el."\r\n";
            }

        }

        file_put_contents($ext.DS.'builder'.DS.$name, $output);

        if($ext == 'js') {
            return dom::create_element('<script>', ['src'=>'/js/builder/'.$name]);
        }
        else {
            return dom::create_element('<link>',
                ['rel'=>'stylesheet',
                'href'=>'/css/builder/'.$name]);
        }
    }
}