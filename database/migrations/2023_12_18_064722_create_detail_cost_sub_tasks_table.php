<?php

use App\Models\CostComponent;
use App\Models\SubTask;
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
        Schema::create('detail_cost_sub_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CostComponent::class);
            $table->foreignIdFor(SubTask::class);
            $table->decimal('volume');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_cost_sub_tasks');
    }
};
