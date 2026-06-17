<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('category')->change();
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null')->after('rejection_reason');
            $table->timestamp('target_completion_date')->nullable()->after('department_id');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['department_id', 'target_completion_date']);
        });
    }
};
