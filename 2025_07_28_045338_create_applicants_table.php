<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('applicants', function (Blueprint $table) {
             $table->id();
             $table->date('year');
             $table->string('season');
             $table->string('first_name');
             $table->string('last_name');
             $table->string('email')->unique();;
             $table->string('phone');
             $table->date('dob');
             $table->string('gender')->nullable();
             $table->string('category')->nullable();
             $table->text('address');
             $table->string('city');
             $table->string('state');
             $table->string('pincode');
             $table->string('institute');
             $table->string('department');
             $table->string('area_of_interest');
             $table->string('educational_qualification');
             $table->string('studying_at_present');
             $table->string('presentely_employed')->nullable();
             $table->string('work_experience')->nullable();
             $table->string('languages')->nullable();
             $table->string('achievements');
             $table->string('sop');
             $table->boolean('declaration')->default(false);
             $table->timestamps();
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
