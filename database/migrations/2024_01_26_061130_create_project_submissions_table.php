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
            $table->string('abstract', 2500)->nullable();
            $table->string('categories', 1000)->nullable();
            $table->string('subject', 10);
            $table->foreignId('professor_id')->constrained('users');
            $table->string('attachments')->nullable();
            $table->string('attachments_names')->nullable();
            $table->foreignId('team_id')->constrained('teams');
            $table->string('academic_year', 10);
            $table->string('term', 5);
            $table->string('status', 10)->default('pending');
            $table->softDeletes();
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
