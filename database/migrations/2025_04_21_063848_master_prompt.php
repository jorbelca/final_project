<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('master_prompts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('content');
            $table->timestamps();
        });

        $path = resource_path('prompts/budget_prompt.txt');
        $content = file_exists($path) ? file_get_contents($path) : '';

        DB::table('master_prompts')->insert([
            'id' => 1,
            'name' => 'prompt',
            'content' => $content,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('master_prompts');
    }
};
