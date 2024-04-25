<?php

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
        Schema::create('user_multi_accounts', function (Blueprint $table) {
            $table->id();
            $table->text('user_ids');
            $table->string('type');
            $table->string('value')->nullable();
            $table->text('message');
            $table->timestamps();

            $table->unique(['user_ids', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_multi_accounts');
    }
};
