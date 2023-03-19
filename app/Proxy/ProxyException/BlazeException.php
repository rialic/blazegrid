<?php

namespace App\Proxy\ProxyException;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

/**
 * Classe que faz o tratamento para escrever no arquivo blaze.log
 * Nesse arquivo poderá ser escrito log de ERROR ou log de INFO
 * A exception BlazeException foi configurada no arquivo logging
 */
class BlazeException
{
    public function handleException($exception, $game)
    {
        if ($exception->statusCode === 200) {
            $this->writeBlazeLogInfoException($exception, $game);

            return;
        }

        $this->writeBlazeLogErrorException($exception, $game);
    }

    private function writeBlazeLogErrorException($exception, $game)
    {
        $code = optional($exception)->statusCode;
        $message = Str::of(optional($exception)->message)->trim();
        $logMessage = "Log from {$game} => Code: {$code} Message: {$message}";

        Log::channel('blaze')->error($logMessage);
    }

    private function writeBlazeLogInfoException($exception, $game)
    {
        $code = optional($exception)->statusCode;
        $message = Str::of(optional($exception)->message)->trim();
        $logMessage = "Log from {$game} => Code: {$code} Message: {$message}";

        Log::channel('blaze')->info($logMessage);
    }
}