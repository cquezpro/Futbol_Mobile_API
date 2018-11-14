<?php

use Illuminate\Database\Seeder;
use App\ClubName;


use App\Helpers\CustomProgressBar;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ClubNamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->command->info("Truncating club_names Table");
        ClubName::truncate();
      
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = File::get('database/data/teams.json');
        $data = json_decode($data);

        $total = count((array)$data);
        $progress = new CustomProgressBar($this->command->getOutput()->createProgressBar($total));

        foreach ($data as $team) {
			$progress->setMessage("saving " . $team->name, 'status');
			$club = ClubName::create([
			    'description'       => $team->description,	   
			]);

			$this->command->info($team->description);
			$club->save();
			$progress->advance();
        }

        $progress->finish();
        $this->command->info("\nFinished\n");
    }
  
}
