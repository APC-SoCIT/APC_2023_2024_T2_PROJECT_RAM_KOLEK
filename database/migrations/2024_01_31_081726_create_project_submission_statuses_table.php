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
        Schema::create('project_submission_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_submission_id')->constrained('project_submissions');
            $table->foreignId('user_id')->constrained('users')->nullable();
            $table->string('type',25);
            $table->string('status',25);
            $table->string('feedback',1000)->nullable();
            $table->timestamps();
        });
    }

    /**
     * 
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_submission_statuses');
    }
};
