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
        Schema::create('scenarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tag_id');
            $table->string('name');
            $table->string('note')->nullable();
            $table->enum('developmentType', ['New Construction', 'Renovation/Remodeling', 'Repair/Maintenance', 'Other']);
            $table->enum('developmentStrategy', ['Build-to-Rent', 'Build-to-Sell', 'Hybrid']);
            $table->enum('endUse', ['Residential', 'Commercial', 'Mixed Use']);
            $table->enum('unitType', ['Apartment', 'Row', 'Other']);
            $table->enum('gfaCalcMethod', ['FSI-Based ', 'Manual']);
            $table->float('fsi', precision: 2);
            $table->float('gfa', precision: 2);
            $table->enum('areaAllocMethod', ['Manual', 'Percentile']);
            $table->unsignedMediumInteger('residentialGFANumber');
            $table->unsignedSmallInteger('residentialGFAPercentage');
            $table->unsignedMediumInteger('commercialGFANumber');
            $table->unsignedSmallInteger('commercialGFAPercentage');
            $table->enum('nfaAreaAllocMethod', ['Manual', 'Percentile']);
            $table->unsignedMediumInteger('residentialNFANumber');
            $table->unsignedSmallInteger('residentialNFAPercentage');
            $table->unsignedMediumInteger('commercialNFANumber');
            $table->unsignedSmallInteger('commercialNFAPercentage');
            $table->timestamps();

            
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users');

            $table
                ->foreign('tag_id')
                ->references('id')
                ->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scenarios');
    }
};
