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
        Schema::create('project_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('abstract')->nullable();
            $table->string('categories')->nullable();
            $table->string('subject');
            $table->string('professor_id');
            $table->string('proofreader_id')->nullable();
            $table->string('attachments')->nullable();
            $table->string('attachments_names')->nullable();
            $table->string('team_id');
            $table->string('academic_year');
            $table->string('term');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_submissions');
    }
};
