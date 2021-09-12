<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Input;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Input::truncate();
        Campaign::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = \Faker\Factory::create();

        $types = ['channel', 'source', 'campaign_name', 'target_url'];

        for ($i = 0; $i < 30; $i++) {
            Campaign::create([
                'campaign_id' => $faker->uuid,
                'author' => rand(1, 10),
            ]);

            for ($j = 0; $j < rand(1, 4); $j++) {
                Input::create([
                    'campaign_id' => $i + 1,
                    'type' => $types[$j],
                    'value' => $faker->words(10, true),
                ]);
                sleep(1);
            }
            sleep(2);
        }
    }
}
