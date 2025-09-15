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
        Schema::table('restaurant_cuisine', function (Blueprint $table) {
            if (!Schema::hasColumn('restaurant_cuisine', 'rank')) {
                $table->integer('rank')->default(0)->after('cuisine_id');
            }
            
            // Add index for better performance
            if (!Schema::hasIndex('restaurant_cuisine', 'restaurant_cuisine_cuisine_id_rank_index')) {
                $table->index(['cuisine_id', 'rank']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_cuisine', function (Blueprint $table) {
            if (Schema::hasColumn('restaurant_cuisine', 'rank')) {
                $table->dropColumn('rank');
            }
            
            if (Schema::hasIndex('restaurant_cuisine', 'restaurant_cuisine_cuisine_id_rank_index')) {
                $table->dropIndex(['cuisine_id', 'rank']);
            }
        });
    }
};