# Pending Booking Cleanup System

## Overview

This system automatically deletes pending (unpaid) bookings after 15 minutes to free up room availability for other customers.

## Features

- **Automatic Cleanup**: Deletes expired pending bookings every 5 minutes
- **Manual Cleanup**: Admin can trigger cleanup manually via API
- **Comprehensive Logging**: All cleanup activities are logged for audit purposes
- **Flexible Timing**: Configurable expiration time (default: 15 minutes)
- **Both Booking Types**: Handles both accommodation and house bookings

## Implementation

### 1. Service Class
- **File**: `app/Services/PendingBookingCleanupService.php`
- **Purpose**: Core logic for cleaning up expired pending bookings
- **Methods**:
  - `cleanupExpiredPendingBookings($minutes = 15)`: Main cleanup method
  - `getExpiredPendingBookingsCount($minutes = 15)`: Get count of expired bookings
  - `isBookingExpired($booking, $minutes = 15)`: Check if specific booking is expired

### 2. Artisan Command
- **File**: `app/Console/Commands/CleanupExpiredPendingBookings.php`
- **Command**: `php artisan bookings:cleanup-expired-pending`
- **Options**: `--minutes=15` (default: 15 minutes)

### 3. Scheduled Task
- **File**: `app/Console/Kernel.php`
- **Schedule**: Runs every 5 minutes
- **Logs**: Output saved to `storage/logs/booking-cleanup.log`

### 4. API Endpoints
- **Manual Cleanup**: `POST /api/admin/cleanup-expired-pending-bookings`
- **Get Count**: `GET /api/admin/expired-pending-bookings-count`

## Usage

### Manual Cleanup
```bash
# Clean up bookings older than 15 minutes (default)
php artisan bookings:cleanup-expired-pending

# Clean up bookings older than 10 minutes
php artisan bookings:cleanup-expired-pending --minutes=10
```

### API Usage
```bash
# Trigger manual cleanup
curl -X POST "https://api-tempoafrica-mobile-v1.tempoapplication.com/api/admin/cleanup-expired-pending-bookings" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"minutes": 15}'

# Get count of expired bookings
curl -X GET "https://api-tempoafrica-mobile-v1.tempoapplication.com/api/admin/expired-pending-bookings-count" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Scheduled Task Setup
To enable automatic cleanup, ensure your server has a cron job running:

```bash
# Add to crontab (run every minute)
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

## Configuration

### Expiration Time
- **Default**: 15 minutes
- **Configurable**: Via command line option or API parameter
- **Consistent**: All parts of the system use the same timing

### Logging
- **Location**: `storage/logs/booking-cleanup.log`
- **Level**: Info for successful operations, Error for failures
- **Details**: Booking ID, reference, customer ID, creation time, expiration time

## Database Impact

### Tables Affected
- `bookings` - Accommodation bookings
- `house_bookings` - House bookings

### Query Logic
```sql
-- Find expired pending bookings
SELECT * FROM bookings 
WHERE is_paid = 0 
AND created_at < NOW() - INTERVAL 15 MINUTE;

SELECT * FROM house_bookings 
WHERE is_paid = 0 
AND created_at < NOW() - INTERVAL 15 MINUTE;
```

## Monitoring

### Check Cleanup Status
```bash
# View cleanup logs
tail -f storage/logs/booking-cleanup.log

# Check scheduled tasks
php artisan schedule:list
```

### Performance Considerations
- **Batch Processing**: Processes bookings in batches to avoid memory issues
- **Background Execution**: Scheduled tasks run in background
- **Overlap Prevention**: Uses `withoutOverlapping()` to prevent multiple instances

## Troubleshooting

### Common Issues
1. **Scheduled task not running**: Check cron job setup
2. **Permission errors**: Ensure proper file permissions
3. **Memory issues**: Monitor server resources during cleanup

### Debug Commands
```bash
# Test cleanup manually
php artisan bookings:cleanup-expired-pending --verbose

# Check for expired bookings
php artisan tinker
>>> App\Services\PendingBookingCleanupService::getExpiredPendingBookingsCount()
```

## Security

- **Authentication Required**: API endpoints require valid authentication
- **Audit Trail**: All cleanup activities are logged
- **Admin Only**: Cleanup endpoints restricted to admin users

## Future Enhancements

- **Email Notifications**: Alert admins when cleanup occurs
- **Dashboard Integration**: Web interface for monitoring
- **Custom Rules**: Different expiration times for different booking types
- **Soft Delete**: Option to soft delete instead of hard delete
