<?php

namespace App\Proxy\Blaze;

class BlazeHeaders
{
    public function getCrashHeader()
    {
        $crashHeader['path'] = config('app.crash_api_path');
        $crashHeader['referer'] = config('app.crash_api_referer');

        return array_merge($this->getHeader(), $crashHeader);
    }

    private function getHeader()
    {
        $header = array_change_key_case(getallheaders());
        $header['authority'] = 'blaze.com';
        $header['accept'] = 'application/json, text/plain, */*';
        $header['accept-encoding'] = 'gzip, deflate, br';
        $header['scheme'] = 'https';
        $header['x-client-language'] = 'pt';
        $header['sec-fetch-site'] = 'same-origin';
        $header['sec-ch-ua'] = 'same-origin';
        $header['sec-fetch-dest'] = 'empty';
        $header['sec-fetch-mode'] = 'cors';
        $header['cache-control'] = 'no-cache';

        $header = array_diff_key($header, array_flip($this->getIgnoreHeaders()));

        return $header;
    }

    private function getIgnoreHeaders()
    {
        return [
            'host',
            'connection',
            'upgrade-insecure-requests',
            'sec-fetch-user'
        ];
    }
}