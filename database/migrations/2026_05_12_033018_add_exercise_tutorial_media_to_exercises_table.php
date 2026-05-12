<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->string('exercise_tutorial_gif_path')->nullable()->after('exercise_tutorial_url');
            $table->string('exercise_tutorial_video_path')->nullable()->after('exercise_tutorial_gif_path');
        });
    }

    public function down(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->dropColumn([
                'exercise_tutorial_gif_path',
                'exercise_tutorial_video_path',
            ]);
        });
    }
};
