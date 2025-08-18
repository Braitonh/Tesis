<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\WelcomeEmployeeNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email functionality for debugging';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing email configuration...');

        // Check mail configuration
        $mailConfig = config('mail');
        $this->info('Mail driver: ' . $mailConfig['default']);
        $this->info('Mail host: ' . $mailConfig['mailers']['smtp']['host']);
        $this->info('Mail username: ' . $mailConfig['mailers']['smtp']['username']);

        // Create a test user
        $this->info('Creating test user...');
        $user = User::create([
            'name' => 'Test Employee',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'role' => 'empleado',
            'dni' => 'TEST' . time(),
            'direccion' => 'Test Address',
            'telefono' => '123456789'
        ]);

        $this->info('User created with ID: ' . $user->id);

        try {
            $this->info('Sending notification...');
            $user->notify(new WelcomeEmployeeNotification('testpass123'));
            $this->info('✓ Notification sent successfully!');
        } catch (\Exception $e) {
            $this->error('✗ Error sending notification: ' . $e->getMessage());
            Log::error('Email test error: ' . $e->getMessage());
        }

        // Clean up
        $user->delete();
        $this->info('Test user deleted.');
    }
}
