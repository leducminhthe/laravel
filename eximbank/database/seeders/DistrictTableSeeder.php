<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provinces = DB::table('el_province')->get();
        $i=0;
        foreach ($provinces as $index=> $province) {
            foreach (range(1,10) as $value) {
                DB::table('el_district')->insert([
                    'id' => ($i+1)*$value,
                    'name' => 'Quận '. $value,
                    'province_id' => $province->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $i=$i+10;
        }
    }
}
