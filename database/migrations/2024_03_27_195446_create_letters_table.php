<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignIdFor(User::class, 'user_id');
            $table->string('title', 250);
            $table->string('slug');
            $table->longText('message');
            $table->timestamp('deliver_at');
            $table->timestamp('delivered_at')->nullable();
            $table->boolean('is_public')->default(false);
            $table->char('publish_status', 1)->default('d'); // draft - published
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};
