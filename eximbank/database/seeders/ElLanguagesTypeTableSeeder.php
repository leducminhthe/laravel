<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ElLanguagesTypeTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('el_languages_type')->delete();

        \DB::table('el_languages_type')->insert(array (
            0 =>
            array (
                'id' => 1,
                'icon' => 'images/i_flag_vietnam.png',
                'key' => 'vi',
                'name' => 'Tiếng việt',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'icon' => 'images/i_flag_england.png',
                'key' => 'en',
                'name' => 'English',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));


    }
}
