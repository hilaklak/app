<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivationCodesTable extends Migration
{
    public function up()
    {
        Schema::create('activation_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'user_id');
            $table->char('type');
            $table->string('code');
            $table->timestamp('expired_at');
            $table->unique(['user_id', 'code']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('activation_codes');
    }
}
