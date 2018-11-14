<?php
use App\Console;
use App\Helpers\CustomProgressBar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConsolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->command->info("Truncating Consoles Table\n");
        Console::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $datas = File::get('database/data/consoles.json');
        $data = json_decode($datas);
        $total = count((array) $data);
        $progress = new CustomProgressBar($this->command->getOutput()->createProgressBar($total));
        foreach ($data as $key => $value) {

            $profile = Console::create([
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
