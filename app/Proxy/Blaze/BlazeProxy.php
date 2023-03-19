<?php

namespace App\Proxy\Blaze;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Pool;
use App\Proxy\ProxyException\BlazeException;
use App\Repository\Queries\CrashRepo as CrashRepo;
use App\ScraperAPI\Scraper;
use stdClass;

/**
 * Essa classe funciona como um proxy na qual acesse os últimos históricos do site da blaze.com
 */
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

    /**
     * Faz uma busca pelo últimos histórico de crash e double
     * Em caso de sucesso chama o método saveResponse() para salvar no banco de dados
     * Em caso de falha, é chamado o handleException da classe BlazeExcepetion que grava o erro no log blaze.log dentro da pasta storage
     */
    public function fetch()
    {
        // Fetch Blaze throught history endpoint
        $responses = $this->fetchByHttpClient();

        foreach ($responses as $game => $gameResponse) {
            $isResponsNotOk = !optional($gameResponse)->successful() && !optional($gameResponse)->ok();

            if ($isResponsNotOk) {
                $exception = new stdClass();
                $exception->error = optional($gameResponse->object())->error;
                $exception->statusCode = $gameResponse->status();

                $this->blazeException->handleException($exception, $game);

                // Após gravar a falha no blaze.log, é feita uma request através do fetchByScraperApi() para tentar recuperar o histórico da blaze.com
                $this->fetchByScraperApi($game);

                continue;
            }

            $response['records'] = optional(optional($gameResponse)->collect())['records'];

            $this->saveResponse($response, $game);
        }
    }

    /**
     * Método que chama Facade HTTP padrão do Laravel que foi pré configurada no arquivo AppServiceProviders
     * Dentro de AppServiceProviders é feita a confirações do clientes de requisições que desejo solicitar
     * Através desse método é feito um pool de requisições que faz um response para o método fetch()
     */
    private function fetchByHttpClient()
    {
        return Http::pool(fn (Pool $pool) => [
            $pool->as('crash')->crash()
            // $pool->as('double')->double()
        ]);
    }

    /**
     * Esse método faz uma requisição através da api de serviços do site https://www.scraperapi.com/
     * Para o correto funcionamento do projeto é necessário que você tenha um cadastro no site https://www.scraperapi.com/
     * Essa api consegue acessar o histórico da blaze.com e retornar os dados para que possa salvar no método saveResponse()
     * Em caso de falha, assumimos que o site blaze.com está com algum problema e gravamos o log no arquivo blaze.log
     * Depois da falha e a escrita no blaze.log é aguardado a próxima requisição
     */
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

    /**
     * Método que salva no banco de dados
     */
    private function saveResponse($response, $game)
    {
        $records = $response['records'];
        $isRecordsEmpty = empty($records) && !is_array($records);

        // Caso o retorno da blaze.com seja 200 OK porém com uma resposta vazia, é gravado o problema no log blaze.log
        if ($isRecordsEmpty) {
            $exception = new stdClass();
            $recordsEncoded = json_encode($records);
            $exception->message = "Blaze returns 200 without records. | records_returned {$recordsEncoded}";
            $exception->statusCode = 200;

            $this->blazeException->handleException($exception, $game);

            return;
        }

        // Seta os parâmetros para buscar os últimos 25 registros que foram salvaos no banco de dados
        $params = ['limit' => 50, 'orderBy' => 'cr_created_at_server', 'direction' => 'desc'];
        $gameRepo = $this->getRepo($game);
        $gameModel = $gameRepo->getEntity();
        $gameList = $gameRepo->getData($params);

        $isGameRepoEmpty = empty($gameRepo->count());

        // Lógica para salvar caso o banco de dados esteja vazio
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

        // Lógica para salvar caso o banco de dados NÃO esteja vazio
        // Nesse loop verificamos todos os itens recuperados do banco de dados e conferimos o último registro e salvamos os novos registros a partir do último, dado uma sequêcia
        // nos dados
        $records = collect($records)->slice(0, $params['limit']);

        $records->each(function ($record) use ($gameModel, $gameList) {
            $isItemAlreadyInList = $gameList->contains('cr_id_server', $record['id']);

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
