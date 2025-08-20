<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Laravel\Passport\Passport;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing API Authentication...\n";

// Check if Passport tables exist
try {
    $clients = DB::table('oauth_clients')->count();
    echo "OAuth Clients: $clients\n";
    
    $personalClients = DB::table('oauth_personal_access_clients')->count();
    echo "Personal Access Clients: $personalClients\n";
    
    if ($clients == 0) {
        echo "No OAuth clients found. Creating personal access client...\n";
        
        // Create personal access client
        $clientId = DB::table('oauth_clients')->insertGetId([
            'user_id' => null,
            'name' => 'Tempo Mobile App Personal Access Client',
            'secret' => 'secret',
            'provider' => 'customers',
            'redirect' => 'http://localhost',
            'personal_access_client' => 1,
            'password_client' => 0,
            'revoked' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $clientId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "Personal access client created with ID: $clientId\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Passport tables might not exist. Running migrations...\n";
    
    // Run Passport migrations
    $artisan = $app->make('Illuminate\Contracts\Console\Kernel');
    $artisan->call('migrate', ['--path' => 'vendor/laravel/passport/database/migrations']);
    
    echo "Migrations completed.\n";
}

echo "Test completed.\n"; 