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
        $tag = preg_replace('/(<) | (>)/', '', $tag);

        $text = !empty($params['text'])? $params['text'] : '';

        foreach($params as $key=>$value) {
            if($key != 'text')
            {
                $tag .= " $key=\"$value\" ";
            }
        }

        if(in_array($tag, ['checkbox', 'radio'])) {
            return "<$tag>$text";
        }

        return "<$tag>$text</$tag>";
    }
}