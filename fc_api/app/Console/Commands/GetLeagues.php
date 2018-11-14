<?php

namespace App\Console\Commands;

use App\Helpers\CustomProgressBar;
use App\League;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class GetLeagues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:leagues_old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtine todas las ligas de la API sportickr.com';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        self::getData();
    }

    private function getData($page = 1)
    {
        $client = new Client(['base_uri' => 'https://sportickr.com/api/v1.0/']);

        $response = $client->request('GET', "leagues?api-key=d24ba528d78707c5a23e36abf9675beb&include=season&page={$page}");
        $leagues = json_decode($response->getBody()->getContents());

        $totalPages = $leagues->meta->pagination->total_pages;
        $currentPage = $leagues->meta->pagination->current_page;

        $total = count((array) $leagues->data);
        $progress = new CustomProgressBar($this->getOutput()->createProgressBar($total));

        foreach ($leagues->data as $league) {
            self::setDataObject($league, $progress);
            $progress->advance();
        }

        if ($totalPages > 1 && $currentPage < $totalPages) {
            $currentPage++;
            self::getData($currentPage);
        }

    }

    private function setDataObject($league, $progress)
    {
        $id = isset($league->id) ? $league->id : '';
        $name = isset($league->name) ? $league->name : '';
        $is_cup = isset($league->is_cup) ? $league->is_cup : '';
        $current_season_id = isset($league->current_season_id) ? $league->current_season_id : '';
        $current_round_id = isset($league->current_round_id) ? $league->current_round_id : '';
        $current_stage_id = isset($league->current_stage_id) ? $league->current_stage_id : '';
        $country_id = isset($league->country_id) ? $league->country_id : '';

        $searchLeague = League::find($id);
        if (!$searchLeague) {
            $progress->setMessage("saving " . $name, 'status');
            $newLeague = League::create([
                "id" => $id,
                "name" => $name,
                "is_cup" => $is_cup,
                "current_season_id" => $current_season_id,
                "current_round_id" => $current_round_id,
                "current_stage_id" => $current_stage_id,
                "country_id" => $country_id,
            ]);
        } else {
            $progress->setMessage("actualizando " . $name, 'status');
            $searchLeague->update([
                "name" => $name,
                "is_cup" => $is_cup,
                "current_season_id" => $current_season_id,
                "current_round_id" => $current_round_id,
                "current_stage_id" => $current_stage_id,
                "country_id" => $country_id,
            ]);
        }

    }
}
