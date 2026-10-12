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
        Schema::create('membership_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('phone', 40);
            $table->string('address', 500);
            $table->string('status')->default('pending');
            $table->text('review_reason')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at');
            $table->timestamps();
            $table->index(['status', 'submitted_at']);
            $table->index(['user_id', 'status']);
        });

        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->restrictOnDelete();
            $table->foreignId('membership_application_id')->unique()->constrained('membership_applications')->restrictOnDelete();
            $table->string('member_number')->nullable()->unique();
            $table->string('phone', 40);
            $table->string('address', 500);
            $table->string('status')->default('active');
            $table->timestamp('joined_at');
            $table->timestamps();
            $table->index(['status', 'joined_at']);
        });

        Schema::create('membership_application_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membership_application_id')->constrained()->restrictOnDelete();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('event');
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->index(['membership_application_id', 'created_at']);
        });

        Schema::create('membership_application_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membership_application_id')->constrained()->restrictOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('path');
            $table->string('mime_type', 120);
            $table->timestamps();
            $table->index(['membership_application_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_application_documents');
        Schema::dropIfExists('membership_application_events');
        Schema::dropIfExists('members');
        Schema::dropIfExists('membership_applications');
    }
};
