<?php

use App\Helpers\CustomProgressBar;
use App\Helpers\Helpers;
use App\Language;
use App\LanguageKey;
use App\LanguageKeyItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageKeysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->command->info("Truncating Language Keys Table");
        LanguageKey::truncate();
        $this->command->info("Truncating Language Key Items Table");
        LanguageKeyItem::truncate();
        $this->command->info("Truncating Languages Table\n");
        Language::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $datas = File::get('database/data/lang_keys.json');

        $datas = json_decode($datas);


        foreach ($datas->language_keys as $key) {
            $total = count((array)$key->trans);
            $progress = new CustomProgressBar($this->command->getOutput()->createProgressBar($total));

            $name = str_plural($key->name);

            $progress->setMessage("saving " . $name, 'status');

            foreach ($key->trans as $tran) {
                $code = strtolower($tran->code);
                $code = str_replace('-', '_', $code);
                $code = str_replace(' ', '_', $code);

                $languageKey = LanguageKey::create([
                    'code' => $code,
                    'name' => strtolower($name),
                ]);

                foreach ($tran->languages as $key => $value) {
                    $languageItem = new LanguageKeyItem([
                        'value' => $value,
                    ]);

                    $language = Language::where('iso_code', $key)->first();
                    if (!$language) {
                        $language = Language::create([
                            'iso_code' => $key,
                        ]);
                    }

                    $languageItem->language_id = $language->id;
                    $languageItem->language_key_id = $languageKey->id;

                    $languageItem->save();
                }
                $progress->advance();
            }
            $progress->finish();
            $this->command->info("\nFinished.\n");
        }
    }
}
