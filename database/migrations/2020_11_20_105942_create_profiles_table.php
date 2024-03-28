<?php

use App\Enums\GenderEnum;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index()->unique();
            $table->foreignIdFor(User::class, 'user_id');
            $table->string('avatar_path')->nullable();
            $table->string('banner_path')->nullable();
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('bio')->nullable();
            $table->char('gender')->default('u');
            $table->string('birthday')->nullable();
            $table->string('site')->nullable();
            $table->string('facebook')->nullable();
            $table->string('telegram')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('twitter')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
