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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('empleado')->after('password');
            $table->string('dni', 20)->unique()->nullable()->after('role');
            $table->string('direccion', 500)->nullable()->after('dni');
            $table->string('telefono', 20)->nullable()->after('direccion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'dni', 'direccion', 'telefono']);
        });
    }
};
