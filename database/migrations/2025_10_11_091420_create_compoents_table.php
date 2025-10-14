<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('components', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable()->default('Custom');
            $table->text('html');
            $table->text('css')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('components');
    }
};