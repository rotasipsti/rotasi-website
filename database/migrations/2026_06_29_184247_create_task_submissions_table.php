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
        Schema::create('task_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->foreignId('participant_id')->constrained('users')->cascadeOnDelete();
            $table->text('submission_text')->nullable();
            $table->string('file_url', 500)->nullable();
            $table->string('file_name', 255)->nullable();
            $table->dateTime('submitted_at')->useCurrent();
            $table->string('status', 20)->default('submitted'); // submitted, evaluated, rejected
            $table->integer('evaluation_score')->nullable();
            $table->text('evaluation_comment')->nullable();
            $table->foreignId('evaluated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('evaluated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_submissions');
    }
};
