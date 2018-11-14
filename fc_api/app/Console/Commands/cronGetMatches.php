<?php

namespace App\Console\Commands;

use App\Events\ClearMatches;
use App\Events\GetMatches;
use App\Matches;
use App\Team;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class cronGetMatches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:matches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get matches from api every minute';

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
     * @param $value Array
     */
    private function setData($value, $newData = false)
    {
        //region SetData
        $newData = array();
        $game_id = null;
        $localteam_id = null;
        $visitorteam_id = null;
        $localteam_name = null;
        $visitorteam_name = null;
        $localteam_patch = null;
        $visitorteam_patch = null;
        $scores = null;
        $formations = null;
        $statistics = null;
        $events = null;
        $time = null;

        if (isset($value->id)) {
            $game_id = $value->id;
            $newData['game_id'] = $game_id;
            $newData['status'] = $value->time->status;
        }

        if (isset($value->localteam_id)) {
            $localteam_id = $value->localteam_id;
            if ($newData) {
                $team = Team::where('team_id', $localteam_id)->first();

                if ($team && $team->count() > 0) {
                    $localteam_patch = $team->logo_path;
                    $localteam_name = $team->name;

                    $newData['localteam_id'] = $localteam_id;
                    $newData['localteam_name'] = $localteam_name;
                    $newData['localteam_patch'] = $localteam_patch;
                }
            }
        }

        if (isset($value->visitorteam_id)) {
            $visitorteam_id = $value->visitorteam_id;

            if ($newData) {
                $team = Team::where('team_id', $visitorteam_id)->first();
                if ($team && $team->count() > 0) {
                    $visitorteam_name = $team->name;
                    $visitorteam_patch = $team->logo_path;

                    $newData['visitorteam_id'] = $visitorteam_id;
                    $newData['visitorteam_name'] = $visitorteam_name;
                    $newData['visitorteam_patch'] = $visitorteam_patch;

                }
            }
        }

        if (isset($value->scores)) {
            $scores = json_encode($value->scores);
        }

        if (isset($value->formations) && !empty((array) $value->formations)) {
            $formations = json_encode($value->formations);
        }

        if (isset($value->statistics)) {
            $statistics = json_encode($value->statistics);
        }

        if (isset($value->events)) {
            $events = json_encode($value->events);
        }

        if (isset($value->time)) {
            $time = json_encode($value->time);
        }

        $newData['formations'] = $formations;
        $newData['scores'] = $scores;
        $newData['statistics'] = $statistics;
        $newData['events'] = $events;
        $newData['time'] = $time;

        return $newData;
        //endregion

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client([
            'base_uri' => 'https://sportickr.com',
        ]);

        $response = $client->request('GET', 'api/v1.0/livescores/now?api-key=d24ba528d78707c5a23e36abf9675beb&include=statistics,events');

        $info = json_decode($response->getBody()->getContents());
        $flag = false;
        $matches = null;
        $isUpdate = false;

        $localMatches = Matches::where('status', '<>', 'FINISHED')->get();

        $aCount = count((array) $info->data);
        $mCount = $localMatches->count();
        if ($mCount <= 0) {
            foreach ($info->data as $data) {
                $newData = self::setData($data, true);
                Matches::create($newData);
            }
        } else if ($mCount > $aCount) {
            $plMaches = $localMatches->pluck("game_id")->toArray();
            $plData = array_column($info->data, "id");
            $IDs = array_diff($plMaches, $plData);
            $dbMatches = Matches::whereIn('game_id', $IDs)->where('status', '<>', 'FINISHED')->update(['status' => 'FINISHED']);
            self::updateData($info, $localMatches);
            event(new ClearMatches());
        } else if ($aCount > $mCount) {
            $plMaches = $localMatches->pluck("game_id")->toArray();
            $plData = array_column($info->data, "id");
            $IDs = array_diff($plData, $plMaches);
            \Log::debug($IDs);
            foreach ($IDs as $id) {
                foreach ($info->data as $data) {
                    if ($data->id == $id) {
                        $newData = self::setData($data, true);
                        Matches::create($newData);
                    }
                }
            }
            self::updateData($info, $localMatches);
            event(new GetMatches(['status' => 'New Matches']));

            //Se agregan los nuevos partidos en la DB.
        } else if ($mCount == $aCount) {
            self::updateData($info, $localMatches);
        }
    }

    private function updateData($info, $localMatches)
    {
        foreach ($info->data as $data) {
            foreach ($localMatches as $matches) {
                if ($data->id === $matches->game_id) {
                    if (count((array) $data->scores) > 0) {
                        if ($data->scores->localteam_score !== $matches->scores->localteam_score) {
                            event(new GetMatches(['status' => 'New Gool']));
                        }

                        if ($matches->scores->visitorteam_score !== $matches->scores->visitorteam_score) {
                            event(new GetMatches(['status' => 'New Gool']));
                        }
                    }

                    if ($matches->localteam_name == null || empty($matches->localteam_name)) {
                        $newData = self::setData($data, true);
                    } else {
                        $newData = self::setData($data);
                    }

                    $matches->update($newData);
                }
            }
        }

    }
}
