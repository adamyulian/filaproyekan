<?php

use App\Models\SubTask;
use App\Models\Task;
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
        Schema::create('detail_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SubTask::class);
            $table->foreignIdFor(Task::class);
            $table->decimal('koefisien');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_tasks');
    }
};
