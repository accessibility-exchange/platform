<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $courses = DB::table('courses')->get();

        $courses->each(function ($course) {
            $author = '{"en": "'.($course->author ?? '').'"}';
            DB::table('courses')
                ->where('id', $course->id)
                ->update(['author' => $author]);
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->json('author')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('author')->change();
        });

        $courses = DB::table('courses')->get();

        $courses->each(function ($course) {
            $author = json_decode($course->author, false);
            $authorName = $author->en ?? $author->fr ?? '';
            DB::table('courses')
                ->where('id', $course->id)
                ->update(['author' => $authorName]);
        });
    }
};
