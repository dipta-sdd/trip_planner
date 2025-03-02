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
        Schema::create('transportations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained('destinations')->onDelete('cascade');
            $table->string('type'); 
            $table->string('departure_location')->nullable();
            $table->string('arrival_location')->nullable();
            $table->dateTime('departure_time')->nullable();
            $table->dateTime('arrival_time')->nullable();
            $table->string('company')->nullable();
            $table->string('booking_reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
// flight, train, bus, taxi, bike, car, motorcycle, boat, subway, tram, helicopter
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transportations');
    }
};
