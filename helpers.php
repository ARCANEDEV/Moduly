<?php

if ( ! function_exists('moduly')) {
    /**
     * Get Moduly
     *
     * @return Arcanedev\Moduly\Moduly
     */
    function moduly()
    {
        return app(Arcanedev\Moduly\Moduly::KEY_NAME);
    }
}
