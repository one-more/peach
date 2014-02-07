<?php

/**
 * Class trait_site_template
 *
 * @author Nikolaev D.
 */
trait trait_site_template {

    /**
     * @return mixed
     */
    public static function get_positions()
    {
        return static::read_params('positions');
    }
}