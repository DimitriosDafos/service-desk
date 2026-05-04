<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->json('custom_fields')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->unique(['tenant_id', 'slug']);
        });

        Schema::create('assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('asset_type_id')->nullable();
            $table->string('name');
            $table->string('serial_number')->nullable();
            $table->string('asset_tag')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance', 'retired'])->default('active');
            $table->date('purchase_date')->nullable();
            $table->date('warranty_expiry')->nullable();
            $table->string('location')->nullable();
            $table->json('custom_fields')->nullable();
            $table->unsignedBigInteger('assigned_user_id')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('asset_type_id')->references('id')->on('asset_types')->onDelete('set null');
            $table->foreign('assigned_user_id')->references('id')->on('users')->onDelete('set null');
            $table->unique(['tenant_id', 'asset_tag']);
        });

        Schema::create('asset_relationships', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('parent_asset_id');
            $table->uuid('child_asset_id');
            $table->string('relationship_type');
            $table->timestamps();

            $table->foreign('parent_asset_id')->references('id')->on('assets')->onDelete('cascade');
            $table->foreign('child_asset_id')->references('id')->on('assets')->onDelete('cascade');
        });

        Schema::create('ticket_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ticket_id');
            $table->uuid('asset_id');
            $table->timestamps();

            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
            $table->unique(['ticket_id', 'asset_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_assets');
        Schema::dropIfExists('asset_relationships');
        Schema::dropIfExists('assets');
        Schema::dropIfExists('asset_types');
    }
};
