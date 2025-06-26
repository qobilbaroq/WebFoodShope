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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullabel();
            $table->string('name');
            $table->string('phone_number');
            $table->text('address');
            $table->boolean('is_primary')->default(false);
            $table->foreignId('user_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropifExists('addresses');
    }
};
