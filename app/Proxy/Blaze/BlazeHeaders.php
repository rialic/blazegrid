<?php

namespace App\Proxy\Blaze;

/**
 * Essa é a classe que na qual carrega todos headers padrões para se fazer um request no site da blaze.com
 * Recomenda-se não alterar essa classe a não ser tiver incluir mais headers para se fazer a request
 */
class BlazeHeaders
{
    public function getCrashHeader()
    {
        // $crashHeader['path'] = config('app.crash_api_path');
        $crashHeader['referer'] = config('app.crash_api_referer');

        return array_merge($this->getHeader(), $crashHeader);
    }

    private function getHeader()
    {
        $header = array_change_key_case(getallheaders());

        $header['authority'] = 'blaze.com';
        $header['method'] = 'GET';
        $header['scheme'] = 'https';
        $header['accept'] = 'application/json, text/plain, */*';
        $header['sec-fetch-dest'] = 'empty';
        $header['sec-fetch-mode'] = 'cors';
        $header['sec-fetch-site'] = 'same-origin';

        $header = array_diff_key($header, array_flip($this->getIgnoreHeaders()));

        return $header;
    }

    private function getIgnoreHeaders()
    {
        return [
            'cookie',
            'host',
            'connection',
            'upgrade-insecure-requests',
            'sec-fetch-user',
            'content-length',
            'content-type',
            'upgrade-insecure-requests',
            'cache-control'
        ];
    }
}