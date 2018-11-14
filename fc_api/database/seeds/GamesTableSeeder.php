<?php
use App\Console;
use App\ConsoleGame;
use App\Game;
use App\Helpers\CustomProgressBar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->command->info("Truncating Games Table\n");
        Game::truncate();
        $this->command->info("Truncating Consoles Table\n");
        Console::truncate();
        $this->command->info("Truncating ConsoleGame Table\n");
        ConsoleGame::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $datas = File::get('database/data/games.json');
        $data = json_decode($datas);
        $total = count((array) $data);
        $progress = new CustomProgressBar($this->command->getOutput()->createProgressBar($total));
        foreach ($data as $key => $value) {
            $game = Game::create([
                'name' => $value->description,
            ]);

            $progress->setMessage("saving " . $value->description, 'status');

            foreach ($value->consoles as $key => $value) {
                $console = Console::where('name', $value->name)->first();

                if (!$console) {
                    $console = Console::create([
                        'name' => $value->name,
                    ]);
                }
                $progress->setMessage("saving console ", 'status');
                $game->consoles()->attach($console);
                $progress->setMessage("saving " . $value->name, 'status');
                $progress->advance();
                $this->command->info("\nSaving\n");
            }
            /**/
        }
        $progress->advance();
    }
}
