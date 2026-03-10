<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('tenant_id')->nullable()->after('id');
            $table->string('role')->default('requester')->after('password');
            $table->string('avatar')->nullable()->after('role');
            $table->string('phone')->nullable()->after('avatar');
            $table->string('department')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('department');

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('set null');
            $table->index('tenant_id');
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropIndex(['tenant_id']);
            $table->dropIndex(['role']);
            $table->dropColumn([
                'tenant_id',
                'role',
                'avatar',
                'phone',
                'department',
                'is_active',
            ]);
        });
    }
};
