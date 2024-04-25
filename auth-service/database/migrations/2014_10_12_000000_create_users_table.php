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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->foreignId('avatar_id')->nullable()->constrained('files')->nullOnDelete();
            $table->string('country', 2)->nullable();
            $table->string('language', 2)->nullable();
            $table->string('timezone')->nullable();
            $table->timestamp('date_of_birth', 30)->nullable()->after('timezone');
            $table->timestamp('phone_verified_at')->comment('Дата подтверждения телефона')->nullable();
            $table->string('phone_verified_code', 10)->comment('Код подтверждения номера телефона')->nullable();
            $table->string('email_verified_hash')->comment('Хэщ подтверждения почты')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
