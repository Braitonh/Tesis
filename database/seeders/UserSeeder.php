<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@fooddesk.com',
            'password' => Hash::make('abc123'),
            'role' => 'admin',
            'dni' => '12345678',
            'direccion' => 'Av. Principal 123, Asunción',
            'telefono' => '0981234567',
            'email_verified_at' => now(),
            'password_created' => true,
        ]);

        // Cliente User
        User::create([
            'name' => 'Juan Pérez',
            'email' => 'cliente@fooddesk.com',
            'password' => Hash::make('abc123'),
            'role' => 'cliente',
            'dni' => '87654321',
            'direccion' => 'Calle Secundaria 456, Asunción',
            'telefono' => '0987654321',
            'email_verified_at' => now(),
            'password_created' => true,
        ]);

        // Empleado Ventas
        User::create([
            'name' => 'María González',
            'email' => 'ventas@fooddesk.com',
            'password' => Hash::make('abc123'),
            'role' => 'ventas',
            'dni' => '11223344',
            'direccion' => 'Av. España 789, Asunción',
            'telefono' => '0981122334',
            'email_verified_at' => now(),
            'password_created' => true,
        ]);

        // Empleado Cocina
        User::create([
            'name' => 'Carlos Rodríguez',
            'email' => 'cocina@fooddesk.com',
            'password' => Hash::make('abc123'),
            'role' => 'cocina',
            'dni' => '55667788',
            'direccion' => 'Calle Brasil 321, Asunción',
            'telefono' => '0985566778',
            'email_verified_at' => now(),
            'password_created' => true,
        ]);

        // Empleado Delivery
        User::create([
            'name' => 'Luis Martínez',
            'email' => 'delivery@fooddesk.com',
            'password' => Hash::make('abc123'),
            'role' => 'delivery',
            'dni' => '99887766',
            'direccion' => 'Av. Mariscal López 654, Asunción',
            'telefono' => '0989988776',
            'email_verified_at' => now(),
            'password_created' => true,
        ]);

        $this->command->info('✓ Usuarios creados exitosamente');
        $this->command->info('');
        $this->command->info('Credenciales de acceso:');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('👤 Admin:    admin@fooddesk.com    / abc123');
        $this->command->info('👤 Cliente:  cliente@fooddesk.com  / abc123');
        $this->command->info('👤 Ventas:   ventas@fooddesk.com   / abc123');
        $this->command->info('👤 Cocina:   cocina@fooddesk.com   / abc123');
        $this->command->info('👤 Delivery: delivery@fooddesk.com / abc123');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}