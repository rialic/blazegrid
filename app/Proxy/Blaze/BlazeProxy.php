<?php

namespace App\Proxy\Blaze;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Pool;
use App\Proxy\ProxyException\BlazeException;
use App\Repository\Queries\CrashRepo as CrashRepo;
use App\ScraperAPI\Scraper;
use stdClass;

class BlazeProxy
{
    private $crashRepo;
    private $scraper;
    private $blazeException;

    public function __construct(CrashRepo $crashRepo, Scraper $scraper, BlazeException $blazeException)
    {
        $this->crashRepo = $crashRepo;
        $this->scraper = $scraper;
        $this->blazeException = $blazeException;
    }

    private function getRepo($game)
    {
        $repoList = [
            'crash' =>  $this->crashRepo
        ];

        return $repoList[$game];
    }

    public function fetch()
    {
        // Fetch Blaze throught history endpoint
        $responses = $this->fetchByHttpClient();

        foreach ($responses as $game => $gameResponse) {
            $isResponsNotOk = !$gameResponse->successful() && !$gameResponse->ok();

            if ($isResponsNotOk) {
                $exception = new stdClass();
                $exception->error = optional($gameResponse->object())->error;
                $exception->statusCode = $gameResponse->status();

                $this->blazeException->handleException($exception, $game);

                continue;
            }

            $response['records'] = optional(optional($gameResponse)->collect())['records'];

            $this->saveResponse($response, $game);
        }
    }

    private function fetchByHttpClient()
    {
        return Http::pool(fn (Pool $pool) => [
            $pool->as('crash')->crash()
            // $pool->as('double')->double()
        ]);
    }

    private function fetchByScraperApi($game)
    {
        $response = $this->scraper->fetch($game);
        $statusCode = $response->code;

        if ($statusCode !== 200) {
            $exception = optional(optional($response)->body)->error;
            $exception->message = "[Scraper API]: {$exception->message}";
            $exception->statusCode = $statusCode;

            $this->blazeException->handleException($exception, $game);

            return;
        }

        $records = optional(optional($response)->body)->records;

        $response = [];
        $response['records'] = (!empty($records)) ? collect($records)->map(fn ($record) => (array) $record)->all() : null;

        $this->saveResponse($response, $game);
    }

    private function saveResponse($response, $game)
    {
        $records = $response['records'];
        $isRecordsEmpty = empty($records) && !is_array($records);

        if ($isRecordsEmpty) {
            $exception = new stdClass();
            $recordsEncoded = json_encode($records);
            $exception->message = "Blaze returns 200 without records. | records_returned {$recordsEncoded}";
            $exception->statusCode = 200;

            $this->blazeException->handleException($exception, $game);

            return;
        }

        $params = ['limit' => 25];
        $gameRepo = $this->getRepo($game);
        $gameModel = $gameRepo->getEntity();
        $gameList = $gameRepo->getData($params);

        $isGameRepoEmpty = empty($gameRepo->count());

        // Save if the repository is empty
        if ($isGameRepoEmpty) {
            if ($game === 'crash') {
                collect($records)->each(function ($record) use ($gameModel) {
                    $gameModel->forceFill([
                        'id_server' => $record['id'],
                        'point' => ($record['crash_point'] === '0') ? 1 : $record['crash_point'],
                        'created_at_server' => $record['created_at']
                    ]);
                    $gameModel->create($gameModel->getAttributes());
                });
            }

            return;
        }

        // Save if the repository is not empty
        collect($records)->each(function ($record) use ($gameModel, $gameList) {
            $isItemAlreadyInList = $gameList->contains('id_server', $record['id']);

            if (!$isItemAlreadyInList) {
                $gameModel->forceFill([
                    'id_server' => $record['id'],
                    'point' => ($record['crash_point'] === '0') ? 1 : $record['crash_point'],
                    'created_at_server' => $record['created_at']
                ]);
                $gameModel->create($gameModel->getAttributes());
            }
        });
    }
}
