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
        Schema::create('documents', function (Blueprint $table) {
             $table->id();
             $table->foreignId('applicant_id')->constrained('applicants')->onDelete('cascade');
             $table->string('photo_with_signature'); // e.g. "CV", "Photo", "Marksheet"
             $table->string('Resume');
             $table->string('marksheet');
             $table->string('supervisor_letter');
             $table->string('no_objection_certificate');
             $table->string('relavant_information');
             $table->string('file_path');     // File path or filename
            $table->string('file_type');
            $table->string('original_name');
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
