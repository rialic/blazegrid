<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /**
         * Essa call faz a chamada do BlazeProxy a cada 10s
         * O método $blazeProxy->fetch() faz as request no histórico do site da blaze.com para o Crash e para o Double
         * Todo o tratamento para salvar os dados é feito no arquivo BlazeProxy
         * Recomenda-se não alterar essa lógica de requisições
         */
        $schedule->call(function() {
            $blazeProxy = app('App\Proxy\Blaze\BlazeProxy');
            $now = now();
            $futherDate = now()->addMinutes(1);
            $interval = 60 / 10;

            do {
                $blazeProxy->fetch();

                if ($futherDate->lte(now())) {
                    $interval = 0;
                }

                try {
                    time_sleep_until($now->addSeconds(10)->timestamp);
                } catch (\Exception $exception) {
                    $interval = 0;
                }
            } while ($interval-- > 1);
        })
        ->everyMinute()
        ->appendOutputTo(storage_path() . '/queue.log');

        /**
         * Essa call verifica se o banco de dados ultrapssou os 15 mil registros
         * Em caso positivo os 5 mil registros mais antigos são apagados
         */
        $schedule->call(function() {
            $count = DB::table('tb_crash')->count();
            $hasReachedMaxLimit = $count >= 15000;

            if ($hasReachedMaxLimit) {
                DB::table('tb_crash')->orderBy('cr_created_at_server', 'asc')->limit(5000)->delete();
            }
        })->everyMinute();

        /**
         * Essa call verifica se o período do plano do usuário premium ou deluxe já ultrapassou o limite de dias contratados
         * Em caso positivo, esses usuários voltam a ser do plano básico
         */
        $schedule->call(function() {
            DB::table('tb_users')
            ->join('tb_plans', 'tb_users.pl_id', '=', 'tb_plans.pl_id')
            ->where('tb_users.us_expiration_plan_date', '<=', now())
            ->where('tb_plans.pl_plan_name', '!=', 'Basic')
            ->update(['tb_users.us_expiration_plan_date' => null, 'tb_users.pl_id' => DB::table('tb_plans')->where('pl_plan_name', 'Basic')->first()->pl_id]);
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
