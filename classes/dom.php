<?php
/**
 * Class dom
 *
 * @author Nikolaev D.
 */
class dom{
    /**
     * @param $tag
     * @param $params
     * @return string
     */
    public static function create_element($tag, $params)
    {
        $pattens = [
            '/</',
            '/>/'
        ];

        $replacements = [
            '',
            ''
        ];

        $tag = preg_replace($pattens, $replacements, $tag);

        $text = !empty($params['text'])? $params['text'] : '';

        foreach($params as $key=>$value) {
            if($key != 'text')
            {
                if($value) {
                    $tag .= " $key=\"$value\" ";
                }
                else {
                    $tag .= " $key ";
                }
            }
        }

        if(in_array($tag, ['checkbox', 'radio', 'link'])) {
            return "<$tag>$text";
        }

        return "<$tag>$text</$tag>";
    }
}