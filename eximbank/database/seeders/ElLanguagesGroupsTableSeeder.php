<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ElLanguagesGroupsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('el_languages_groups')->delete();

        \DB::table('el_languages_groups')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'Menu',
                'slug' => 'menu',
                'created_at' => '2021-11-11 17:17:07',
                'updated_at' => '2021-11-11 17:17:07',
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'Button',
                'slug' => 'button',
                'created_at' => '2021-11-11 17:18:35',
                'updated_at' => '2021-11-11 17:18:35',
            ),
            2 =>
            array (
                'id' => 3,
                'name' => 'Dashboard',
                'slug' => 'dashboard',
                'created_at' => '2021-11-11 17:19:00',
                'updated_at' => '2021-11-11 17:19:00',
            ),
            3 =>
            array (
                'id' => 4,
                'name' => 'Setting',
                'slug' => 'setting',
                'created_at' => '2021-11-11 17:19:21',
                'updated_at' => '2021-11-11 17:19:21',
            ),
            4 =>
            array (
                'id' => 5,
                'name' => 'Category',
                'slug' => 'category',
                'created_at' => '2021-11-11 17:19:48',
                'updated_at' => '2021-11-11 17:19:48',
            ),
            5 =>
            array (
                'id' => 6,
                'name' => 'Profile',
                'slug' => 'profile',
                'created_at' => '2021-11-11 17:21:27',
                'updated_at' => '2021-11-11 17:21:27',
            ),
            6 =>
            array (
                'id' => 7,
                'name' => 'Career_path',
                'slug' => 'career_path',
                'created_at' => '2021-11-11 17:22:06',
                'updated_at' => '2021-11-11 17:22:06',
            ),
            7 =>
            array (
                'id' => 8,
                'name' => 'Survey',
                'slug' => 'survey',
                'created_at' => '2021-11-11 17:22:52',
                'updated_at' => '2021-11-11 17:22:52',
            ),
            8 =>
            array (
                'id' => 9,
                'name' => 'Handle_situations',
                'slug' => 'handle_situations',
                'created_at' => '2021-11-11 17:23:12',
                'updated_at' => '2021-11-11 17:23:12',
            ),
            9 =>
            array (
                'id' => 10,
                'name' => 'Forums',
                'slug' => 'forums',
                'created_at' => '2021-11-11 17:23:36',
                'updated_at' => '2021-11-11 17:23:36',
            ),
            10 =>
            array (
                'id' => 11,
                'name' => 'Suggest',
                'slug' => 'suggest',
                'created_at' => '2021-11-11 17:24:01',
                'updated_at' => '2021-11-11 17:24:01',
            ),
            11 =>
            array (
                'id' => 12,
                'name' => 'Note',
                'slug' => 'note',
                'created_at' => '2021-11-11 17:24:21',
                'updated_at' => '2021-11-11 17:24:21',
            ),
            12 =>
            array (
                'id' => 13,
                'name' => 'History_management',
                'slug' => 'history_management',
                'created_at' => '2021-11-11 17:24:36',
                'updated_at' => '2021-11-11 17:24:36',
            ),
            13 =>
            array (
                'id' => 14,
                'name' => 'FAQ',
                'slug' => 'faq',
                'created_at' => '2021-11-11 17:24:52',
                'updated_at' => '2021-11-11 17:24:52',
            ),
            14 =>
            array (
                'id' => 15,
                'name' => 'Guide',
                'slug' => 'guide',
                'created_at' => '2021-11-11 17:25:10',
                'updated_at' => '2021-11-11 17:25:10',
            ),
            15 =>
            array (
                'id' => 16,
                'name' => 'Suggest_plan',
                'slug' => 'suggest_plan',
                'created_at' => '2021-11-11 17:25:47',
                'updated_at' => '2021-11-11 17:25:47',
            ),
            16 =>
            array (
                'id' => 17,
                'name' => 'API',
                'slug' => 'api',
                'created_at' => '2021-11-11 17:26:09',
                'updated_at' => '2021-11-11 17:26:09',
            ),
            17 =>
            array (
                'id' => 18,
                'name' => 'Training',
                'slug' => 'training',
                'created_at' => '2021-11-11 17:26:28',
                'updated_at' => '2021-11-11 17:26:28',
            ),
            18 =>
            array (
                'id' => 19,
                'name' => 'Question_lib',
                'slug' => 'question_lib',
                'created_at' => '2021-11-11 17:26:55',
                'updated_at' => '2021-11-11 17:26:55',
            ),
            19 =>
            array (
                'id' => 20,
                'name' => 'Quiz',
                'slug' => 'quiz',
                'created_at' => '2021-11-11 17:27:28',
                'updated_at' => '2021-11-11 17:27:28',
            ),
            20 =>
            array (
                'id' => 21,
                'name' => 'Library',
                'slug' => 'library',
                'created_at' => '2021-11-11 17:27:54',
                'updated_at' => '2021-11-11 17:27:54',
            ),
            21 =>
            array (
                'id' => 22,
                'name' => 'News',
                'slug' => 'news',
                'created_at' => '2021-11-11 17:28:14',
                'updated_at' => '2021-11-11 17:28:14',
            ),
            22 =>
            array (
                'id' => 23,
                'name' => 'Promotion',
                'slug' => 'promotion',
                'created_at' => '2021-11-11 17:28:34',
                'updated_at' => '2021-11-11 17:28:34',
            ),
            23 =>
            array (
                'id' => 24,
                'name' => 'Video_training_materials',
                'slug' => 'video_training_materials',
                'created_at' => '2021-11-11 17:28:50',
                'updated_at' => '2021-11-11 17:28:50',
            ),
            24 =>
            array (
                'id' => 25,
                'name' => 'Role',
                'slug' => 'role',
                'created_at' => '2021-11-11 17:29:12',
                'updated_at' => '2021-11-11 17:29:12',
            ),
            25 =>
            array (
                'id' => 26,
                'name' => 'Role_unit',
                'slug' => 'role_unit',
                'created_at' => '2021-11-11 17:29:33',
                'updated_at' => '2021-11-11 17:29:33',
            ),
            26 =>
            array (
                'id' => 27,
                'name' => 'Calendar',
                'slug' => 'calendar',
                'created_at' => '2021-11-11 17:29:49',
                'updated_at' => '2021-11-11 17:29:49',
            ),
            27 =>
            array (
                'id' => 28,
                'name' => 'Other',
                'slug' => 'other',
                'created_at' => '2021-11-11 17:30:10',
                'updated_at' => '2021-11-11 17:30:10',
            ),
            28 =>
            array (
                'id' => 29,
                'name' => 'Core',
                'slug' => 'core',
                'created_at' => '2021-11-11 17:30:10',
                'updated_at' => '2021-11-11 17:30:10',
            ),
            29 =>
            array (
                'id' => 30,
                'name' => 'Report',
                'slug' => 'report',
                'created_at' => '2021-11-11 17:30:10',
                'updated_at' => '2021-11-11 17:30:10',
            ),
        ));


    }
}
