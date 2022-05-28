<?php

namespace App\ScraperAPI;

use App\Proxy\Blaze\BlazeHeaders;

class Scraper
{
    private $scraperClient;
    private $blazeHeader;

    public function __construct()
    {
        $this->scraperClient = new \ScraperAPI\Client(env('SCRAPER_KEY'));
        $this->blazeHeader = new BlazeHeaders();
    }

    public function fetch($game)
    {
        return $this->scraperClient->get($this->getGameHistoryApi($game), $this->getScraperScope($game));
    }

    private function getScraperScope($game)
    {
        return [
            'headers' => $this->getGameHeader($game),
            'country_code' => 'us',
            'device_type' => 'desktop',
            'premium' => false,
            'render' => false,
            'session_number' => 99,
            'autoparse' => false,
            'retry' => 3,
            'timeout' => 60
        ];
    }

    private function getGameHeader($game)
    {
        $gameHeaderList = [
            'crash' =>  $this->blazeHeader->getCrashHeader()
        ];

        return $gameHeaderList[$game];
    }

    private function getGameHistoryApi($game)
    {
        $gameHistoryApiList = [
            'crash' =>  config('app.crash_history_api')
        ];

        return $gameHistoryApiList[$game];
    }
}
