<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Actualizar el enum de metodo_pago_preferido para incluir mercado_pago
        // Nota: MySQL requiere recrear la columna para modificar ENUM
        DB::statement("ALTER TABLE pedidos MODIFY COLUMN metodo_pago_preferido ENUM('efectivo', 'tarjeta_credito', 'tarjeta_debito', 'billetera_digital', 'mercado_pago') DEFAULT 'efectivo'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir el enum
        DB::statement("ALTER TABLE pedidos MODIFY COLUMN metodo_pago_preferido ENUM('efectivo', 'tarjeta_credito', 'tarjeta_debito', 'billetera_digital') DEFAULT 'efectivo'");
    }
};
