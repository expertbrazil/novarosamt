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
        Schema::table('banners', function (Blueprint $table) {
            $table->string('title')->after('id');
            $table->string('image_desktop')->nullable()->after('title');
            $table->string('image_mobile')->nullable()->after('image_desktop');
            $table->boolean('is_active')->default(true)->after('image_mobile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('banners')) {
            Schema::table('banners', function (Blueprint $table) {
                if (Schema::hasColumn('banners', 'title')) {
                    $table->dropColumn(['title', 'image_desktop', 'image_mobile', 'is_active']);
                }
            });
        }
    }
};
