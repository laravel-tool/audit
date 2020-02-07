<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('audit.table'), function (Blueprint $table) {
            $table->increments('id');
            $table->nullableMorphs('user');
            $table->nullableMorphs('model');
            $table->string('event');
            $table->json('changes')->comment('key:[old,new]');
            $table->nullableMorphs('parent');
            $table->ipAddress('ip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('audit.table'));
    }
}
