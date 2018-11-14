<?php

namespace App\Console\Commands;

use App\League;
use App\Season;
use App\Team;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Validation\Rule;

class LeaguesTeams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:leagues {--teams}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get leagues lists and its respective teams';

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
        $this->getLeagues();
    }

    private function getLeagues($page = 1)
    {
        $withTeams = $this->option('teams');

        $url = env('SOCCER_API_URL_BASE');

        $client = new Client(['base_uri' => $url . "leagues/"]);
        $urlComp = env("SOCCER_API_URL_COM");

        $response = $client->request('GET', $urlComp . "&include=season");
        $leagues = json_decode($response->getBody()->getContents());

        $header = ['id', 'name', 'is_cup', 'current_season_id', "current_round_id", 'current_stage_id'];
        $rows = [];

        $tmHeaders = [
            "id",
            "legacy_id",
            "name",
            "short_code",
            "twitter",
            "country_id",
            "founded",
            "logo_path",
        ];
        foreach ($leagues->data as $league) {
            $leagueData = [
                "id" => (int) $league->id,
                "name" => $league->name,
                "is_cup" => (boolean) $league->is_cup,
                "current_season_id" => $league->current_season_id,
                "current_round_id" => $league->current_round_id,
                "current_stage_id" => $league->current_stage_id,
            ];

            $this->comment("{$league->id}: {$league->name} | Season: {$league->season->data->name}");

            $leagueDB = League::find((int) $league->id);
            if (!$leagueDB) {
                $leagueDB = League::create($leagueData);
            } else {
                if ($leagueDB->current_season_id != $league->current_season_id) {
                    $leagueDB->current_season_id = $league->current_season_id;
                }

                if ($leagueDB->name != $league->name) {
                    $leagueDB->name = $league->name;
                }

                if ($leagueDB->is_cup != (boolean) $league->is_cup) {
                    $leagueDB->is_cup = (boolean) $league->is_cup;
                }

                if ($leagueDB->current_season_id != $league->current_season_id) {
                    $leagueDB->current_season_id = $league->current_season_id;
                    $currentSeasonChange = true;
                }

                if ($leagueDB->current_round_id != $league->current_round_id) {
                    $leagueDB->current_round_id = $league->current_round_id;
                }

                if ($leagueDB->current_stage_id != $league->current_stage_id) {
                    $leagueDB->current_stage_id = $league->current_stage_id;
                }

                $leagueDB->save();
            }

            $seasonData = [
                "id" => (int) $league->season->data->id,
                "name" => $league->season->data->name,
                "league_id" => $league->season->data->league_id,
                "is_current_season" => $league->season->data->is_current_season,
                "current_round_id" => $league->season->data->current_round_id,
                "current_stage_id" => $league->season->data->current_stage_id,
            ];

            $seasonDB = Season::find((int) $league->season->data->id);

            if (!$seasonDB) {
                $seasonDB = new Season($seasonData);
                $seasonDB->save();
            } else {
                if ($seasonDB->name != $league->season->data->name) {
                    $seasonDB->name = $league->season->data->name;
                }

                if ($seasonDB->is_current_season != (boolean) $league->season->data->is_current_season) {
                    $seasonDB->is_current_season = (boolean) $league->season->data->is_current_season;
                }

                if ($seasonDB->current_round_id != $league->season->data->current_round_id) {
                    $seasonDB->current_round_id = $league->season->data->current_round_id;
                }

                if ($seasonDB->current_stage_id != $league->season->data->current_stage_id) {
                    $seasonDB->current_stage_id = $league->season->data->current_stage_id;
                }

                $seasonDB->save();
            }

            if ($withTeams) {
                $client = new Client(['base_uri' => $url . "teams/season/{$league->current_season_id}/"]);

                $urlComp = env("SOCCER_API_URL_COM");
                $response = $client->request('GET', $urlComp);
                $results = json_decode($response->getBody()->getContents());
                foreach ($results->data as $team) {
                    $teamData = [
                        "id" => (int) $team->id,
                        "name" => $team->name,
                        "short_code" => $team->short_code,
                        "twitter" => $team->twitter,
                        "founded" => $team->founded,
                        "logo_path" => $team->logo_path,
                    ];

                    $this->info("Team: {$team->name}");
                    $teamDB = Team::find((int) $team->id);

                    if (!$teamDB) {
                        $this->error('Creando Team');
                        $teamDB = new Team($teamData);
                        $teamDB->save();
                    } else {
                        $this->error('Editando Team');

                        if ($teamDB->name != $team->name) {
                            $teamDB->name != $team->name;
                        }
                        if ($teamDB->short_code != $team->short_code) {
                            $teamDB->short_code != $team->short_code;
                        }
                        if ($teamDB->twitter != $team->twitter) {
                            $teamDB->twitter != $team->twitter;
                        }
                        if ($teamDB->founded != $team->founded) {
                            $teamDB->founded != $team->founded;
                        }
                        if ($teamDB->logo_path != $team->logo_path) {
                            $teamDB->logo_path != $team->logo_path;
                        }

                        $teamDB->save();
                    }

                    if ($teamDB->seasons->count() <= 0) {
                        $this->error("Team: {$teamDB->id} | Season: {$seasonDB->id}");
                        $teamDB->seasons()->attach($seasonDB);
                    } else {
                        $seasonId = $seasonDB->id;

                        $validator = \Validator::make(['team_id' => $teamDB->id], [
                            'team_id' => Rule::unique('season_team')->where(function ($query) use ($seasonId) {
                                $query->where('season_id', $seasonId);
                            }),
                        ]);

                        if (!$validator->fails()) {
                            $teamDB->seasons()->attach($seasonDB);
                        }

                    }
                }

            }
        }

    }
}
