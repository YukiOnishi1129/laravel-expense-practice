<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expense_approval_histories', function (Blueprint $table) {
            // 前からある履歴にはコメントがないので、空を許す
            $table->text('comment')->nullable()->after('action');
        });
    }

    public function down(): void
    {
        Schema::table('expense_approval_histories', function (Blueprint $table) {
            $table->dropColumn('comment');
        });
    }
};
