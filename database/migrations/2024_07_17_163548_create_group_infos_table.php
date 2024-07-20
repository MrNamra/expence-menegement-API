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
        Schema::create('group_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->references('id')->on('groups');
            $table->string('name');
            $table->decimal('paid', 12,2)->default(0);
            $table->decimal('amm_to_paid', 12,2)->default(0);
            $table->tinyInteger('isPaid')->comment('1 => paid, 0 => not paid')->default(0);
            $table->tinyInteger('paidby')->nullable();
            $table->timestamps();
            $table->softDeletes()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_infos');
    }
};
