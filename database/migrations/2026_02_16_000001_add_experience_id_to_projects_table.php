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
        $driver = Schema::getConnection()->getDriverName();
        Schema::table('projects', function (Blueprint $table) use ($driver) {
            if ($driver === 'sqlite') {
                $table->unsignedBigInteger('experience_id')->nullable();
            } else {
                $table->foreignId('experience_id')
                    ->nullable()
                    ->constrained('experiences')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        Schema::table('projects', function (Blueprint $table) use ($driver) {
            if ($driver !== 'sqlite') {
                $table->dropForeign(['experience_id']);
            }
            $table->dropColumn('experience_id');
        });
    }
};
