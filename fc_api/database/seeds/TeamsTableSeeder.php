<?php
use App\Helpers\CustomProgressBar;
use App\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->command->info("Truncating teams Table");
        Team::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $data = File::get('database/data/teams.json');
        $data = json_decode($data);
        $total = count((array) $data);
        $progress = new CustomProgressBar($this->command->getOutput()->createProgressBar($total));
        foreach ($data as $team) {
            $progress->setMessage("saving " . $team->name, 'status');
            $club = Team::create([
                'name' => $team->name,
                'team_id' => $team->team_id,
                'logo_path' => $team->logo_path,
            ]);
            $club->save();
            $progress->advance();
        }
        $progress->finish();
        $this->command->info("\nFinished\n");
    }
}
