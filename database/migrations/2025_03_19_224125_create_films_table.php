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
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('title');                          
            $table->text('description');                    
            $table->string('image')->nullable();              
            $table->float('duration');                        
            $table->integer('minimum_age');
            $table->string('genre');
            $table->timestamps();
            $table->foreignId('user_id')->constrained();    
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};
