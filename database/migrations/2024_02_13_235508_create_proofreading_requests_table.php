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
        Schema::create('proofreading_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_submission_id')->constrained('project_submissions');
            $table->foreignId('owner_id')->constrained('users');
            $table->string('phone_number', 15);
            $table->foreignId('endorser_id')->nullable()->constrained('users')->nullable();
            $table->foreignId('executive_director_id')->nullable()->constrained('users');
            $table->foreignId('proofreader_id')->nullable()->constrained('users');
            $table->string('number_pages', 10);
            $table->string('number_words', 10);
            $table->string('received_date')->nullable();
            $table->string('released_date')->nullable();
            $table->string('attachments')->nullable();
            $table->string('attachments_names')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proofreading_requests');
    }
};
