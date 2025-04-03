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
        Schema::table('businesses', function ($table) {
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
        });
    }
    
    public function down(): void
    {
        Schema::table('businesses', function ($table) {
            $table->dropColumn(['address', 'phone']);
        });
    }
    
};
