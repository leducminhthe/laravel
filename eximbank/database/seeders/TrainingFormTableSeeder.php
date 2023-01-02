<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory;

class TrainingFormTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        foreach (range(1,5) as $index) {
            DB::table('el_training_form')->insert([
                'code'=> Str::random(5),
                'name'=> 'Hình thức đào tạo #'. $index,
                'created_at' => $faker->dateTimeBetween(),
                'updated_at' => $faker->dateTimeBetween()
            ]);
        }
    }
}
