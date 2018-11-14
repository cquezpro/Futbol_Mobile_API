<?php

use App\City;
use App\Country;
use App\Helpers\CustomProgressBar;
use App\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->command->info("Truncating cities Table");
        City::truncate();
        $this->command->info("Truncating states Table");
        State::truncate();
        $this->command->info("Truncating countries Table\n");
        Country::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = File::get('database/data/countries.json');

        $data = json_decode($data);
        $total = count((array) $data);
        $progress = new CustomProgressBar($this->command->getOutput()->createProgressBar($total));

        foreach ($data as $countries) {
            foreach ($countries as $country) {
                $progress->setMessage("saving " . $country->name, 'status');
                $newCountry = Country::create([
                    'name' => $country->name,
                    'iso2' => $country->iso2,
                    'iso3' => $country->iso3,
                    'iso_num' => $country->iso_num,
                    'phone_code' => $country->phone_code,
                ]);

                foreach ($country->states as $state) {
                    $newState = new State(['name' => $state->name]);
                    $newCountry->states()->save($newState);

                    foreach ($state->cities as $city) {
                        $newCity = new City([
                            'name' => $city->name,
                            'format_string' => ucfirst($city->name . ", " . ucfirst($state->name) . ", " . ucfirst($country->name)),
                        ]);

                        $newState->cities()->save($newCity);
                    }
                }
            }

            $progress->advance();

        }

        $progress->finish();
        $this->command->info("\nFinished\n");
    }
}
