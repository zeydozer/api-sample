<?php

namespace Database\Seeders;

use DB, Str, Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $lang = ['en', 'tr', 'fr', 'nl', 'ar'];
        $os = ['apple', 'google'];
        for ($i = 0; $i < 1000000; $i++) {
            $u_id = Str::random(100);
            $app_id = random_int(1, 10);
            DB::table('users')->insert([
                'u_id' => $u_id,
                'app_id' => $app_id,
                'lang' => $lang[array_rand($lang)],
                'os' => $os[array_rand($os)],
                'token' => Hash::make(Str::random(100))
            ]);
            DB::table('subscriptions')->insert([
                'u_id' => $u_id,
                'app_id' => $app_id,
                'finished_at' => now()->subDays(rand(-90, 90))->format('Y-m-d H:i:s'),
                'is_finished' => rand(0, 1),
                'is_started' => rand(0, 1),
                'is_renewed' => rand(0, 1)
            ]);
        }
    }
}
