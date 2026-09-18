<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->foreignId('dealer_id')->constrained()->restrictOnDelete();
            $table->foreignId('submitted_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('assigned_support_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('submitter_name');
            $table->string('designation');
            $table->string('branch');
            $table->string('area');
            $table->string('request_type');
            $table->enum('priority', ['low', 'regular', 'high', 'urgent'])->default('regular');
            $table->text('description');
            $table->date('request_date');
            $table->date('due_date');
            $table->enum('status', ['pending', 'on_going', 'in_progress', 'awaiting_dealer', 'completed'])->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'due_date']);
            $table->index(['dealer_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_requests');
    }
};
