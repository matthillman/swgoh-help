<?php

if (!function_exists('guzzle')) {
    /**
     * Return a guzzle client
     *
     * @param  string  $value
     * @param  array  $options
     * @return GuzzleHttp\Client
     */
    function guzzle() {
        return app('guzzle');
    }
}

if (!function_exists('swgoh')) {
    /**
     * Return a swgoh.help API client
     *
     * @param  string  $value
     * @param  array  $options
     * @return SwgohHelp\API
     */
    function swgoh() {
        return app('swgoh');
    }
}