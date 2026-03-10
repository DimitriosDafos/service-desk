<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('ticket_number')->unique();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->enum('status', ['new', 'triaged', 'in_progress', 'pending', 'resolved', 'closed', 'cancelled'])->default('new');
            $table->enum('priority', ['critical', 'high', 'medium', 'low'])->default('medium');
            $table->enum('impact', ['critical', 'high', 'medium', 'low'])->default('medium');
            $table->unsignedBigInteger('requester_id');
            $table->unsignedBigInteger('assigned_agent_id')->nullable();
            $table->uuid('assigned_group_id')->nullable();
            $table->uuid('queue_id')->nullable();
            $table->uuid('sla_policy_id')->nullable();
            $table->uuid('parent_ticket_id')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('first_response_at')->nullable();
            $table->json('custom_fields')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('requester_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('assigned_agent_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('assigned_group_id')->references('id')->on('groups')->onDelete('set null');
            $table->foreign('sla_policy_id')->references('id')->on('sla_policies')->onDelete('set null');

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'assigned_agent_id']);
            $table->index(['tenant_id', 'queue_id']);
        });

        Schema::create('ticket_comments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ticket_id');
            $table->unsignedBigInteger('user_id');
            $table->longText('content');
            $table->boolean('is_internal')->default(false);
            $table->timestamps();

            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
        });

        Schema::create('ticket_attachments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ticket_id');
            $table->unsignedBigInteger('user_id');
            $table->string('filename');
            $table->string('original_filename');
            $table->string('mime_type');
            $table->integer('size');
            $table->string('path');
            $table->timestamps();

            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
        });

        Schema::create('ticket_tags', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('name');
            $table->string('slug');
            $table->string('color')->default('#6b7280');
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->unique(['tenant_id', 'slug']);
        });

        Schema::create('ticket_ticket_tag', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ticket_id');
            $table->uuid('ticket_tag_id');
            $table->timestamps();

            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('ticket_tag_id')->references('id')->on('ticket_tags')->onDelete('cascade');
            $table->unique(['ticket_id', 'ticket_tag_id']);
        });

        Schema::create('sla_breaches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('ticket_id');
            $table->uuid('sla_policy_id');
            $table->enum('breach_type', ['response', 'resolution']);
            $table->timestamp('breached_at');
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('sla_policy_id')->references('id')->on('sla_policies')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sla_breaches');
        Schema::dropIfExists('ticket_ticket_tag');
        Schema::dropIfExists('ticket_tags');
        Schema::dropIfExists('ticket_attachments');
        Schema::dropIfExists('ticket_comments');
        Schema::dropIfExists('tickets');
    }
};
