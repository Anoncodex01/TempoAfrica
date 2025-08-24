<?php

namespace App\Console\Commands;

use App\Services\PendingBookingCleanupService;
use Illuminate\Console\Command;

class CleanupExpiredPendingBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:cleanup-expired-pending {--minutes=15 : Minutes after which pending bookings expire}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired pending bookings that are older than specified minutes (default: 15 minutes)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $minutes = $this->option('minutes');

        $this->info("Cleaning up pending bookings older than {$minutes} minutes...");

        $result = PendingBookingCleanupService::cleanupExpiredPendingBookings($minutes);

        $totalDeleted = $result['total_deleted'];

        if ($totalDeleted > 0) {
            $this->info("✅ Successfully deleted {$totalDeleted} expired pending bookings:");
            $this->info("   - Accommodation bookings: {$result['accommodation_deleted']}");
            $this->info("   - House bookings: {$result['house_deleted']}");
        } else {
            $this->info("ℹ️  No expired pending bookings found.");
        }

        return 0;
    }
}
