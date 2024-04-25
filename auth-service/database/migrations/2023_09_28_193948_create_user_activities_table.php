<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('fingerprint', 32);
            $table->string('platform')->nullable();
            $table->string('browser_name')->nullable();
            $table->ipAddress('ip')->nullable();
            $table->timestamp('last_activity_at');
            $table->timestamps();

            $table->index(['user_id']);
            $table->unique(['fingerprint', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activities');
    }
};
