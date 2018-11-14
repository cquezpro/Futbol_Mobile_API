<?php
use App\Helpers\CustomProgressBar;
use App\Helpers\Helpers;
use App\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->command->info("Truncating Profiles Table\n");
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $datas = File::get('database/data/profiles.json');
        $data = json_decode($datas);
        $total = count((array)$data);
        $progress = new CustomProgressBar($this->command->getOutput()->createProgressBar($total));
        foreach ($data as $key => $value) {
            
           
            $profile = Profile::create([
                'description' => $value->description,
            ]);
            $progress->setMessage("saving " . $value->description, 'status');
            $progress->advance();
               $this->command->info("\nSaving\n");
        }
       // $progress->finish();
        $this->command->info("\nFinished.\n");

    } 
}