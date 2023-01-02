<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElLanguagesGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_languages_groups', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name');
			$table->string('slug');
			$table->timestamps();
		});

        $this->insertDB();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_languages_groups');
    }

    public function insertDB(){
        \DB::table('el_languages_groups')->insert([
            [
                "name" => "Menu",
                "slug" => "menu"
            ],
            [
                "name" => "Button",
                "slug" => "button"
            ],
            [
                "name" => "Dashboard",
                "slug" => "dashboard"
            ],
            [
                "name" => "Setting",
                "slug" => "setting"
            ],
            [
                "name" => "Category",
                "slug" => "category"
            ],
            [
                "name" => "Profile",
                "slug" => "profile"
            ],
            [
                "name" => "Career_path",
                "slug" => "career_path"
            ],
            [
                "name" => "Survey",
                "slug" => "survey"
            ],
            [
                "name" => "Handle_situations",
                "slug" => "handle_situations"
            ],
            [
                "name" => "Forums",
                "slug" => "forums"
            ],
            [
                "name" => "Suggest",
                "slug" => "suggest"
            ],
            [
                "name" => "Note",
                "slug" => "note"
            ],
            [
                "name" => "History_management",
                "slug" => "history_management"
            ],
            [
                "name" => "FAQ",
                "slug" => "faq"
            ],
            [
                "name" => "Guide",
                "slug" => "guide"
            ],
            [
                "name" => "Suggest_plan",
                "slug" => "suggest_plan"
            ],
            [
                "name" => "API",
                "slug" => "api"
            ],
            [
                "name" => "Training",
                "slug" => "training"
            ],
            [
                "name" => "Question_lib",
                "slug" => "question_lib"
            ],
            [
                "name" => "Quiz",
                "slug" => "quiz"
            ],
            [
                "name" => "Library",
                "slug" => "library"
            ],
            [
                "name" => "News",
                "slug" => "news"
            ],
            [
                "name" => "Promotion",
                "slug" => "promotion"
            ],
            [
                "name" => "Video_training_materials",
                "slug" => "video_training_materials"
            ],
            [
                "name" => "Role",
                "slug" => "role"
            ],
            [
                "name" => "Role_unit",
                "slug" => "role_unit"
            ],
            [
                "name" => "Calendar",
                "slug" => "calendar"
            ],
            [
                "name" => "Other",
                "slug" => "other"
            ],
        ]);
    }
}
