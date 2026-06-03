<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('registrations', 'status')) {
                $table->string('status')->default('pending')->after('total_amount');
            }
            if (!Schema::hasColumn('registrations', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['status', 'approved_at']);
        });
    }
};
