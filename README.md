# TempoAfrica

A Laravel-based accommodation and house booking management system for Africa.

## Features

- Accommodation management
- House booking system
- Customer management
- Payment integration (DPO)
- SMS notifications (Beem Africa, Airtel)
- Firebase integration
- Admin dashboard
- Responsive UI

## Requirements

- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL 5.7 or higher
- Laravel 10.x

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Anoncodex01/TempoAfrica.git
   cd TempoAfrica
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure your .env file**
   - Set your database credentials
   - Configure Firebase credentials
   - Set up SMS service keys
   - Configure payment gateway settings

6. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Storage Setup**
   ```bash
   php artisan storage:link
   ```

8. **Build Assets**
   ```bash
   npm run build
   ```

9. **Start Development Server**
   ```bash
   php artisan serve
   npm run dev
   ```

## Environment Variables

### Required Configuration

Create a `.env` file based on `.env.example` and configure the following:

#### Database
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tempoafrica
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

#### Firebase (for notifications)
```
FIREBASE_CREDENTIALS_PATH=storage/firebase/firebase_credentials.json
FIREBASE_PROJECT_ID=your_project_id
```

#### SMS Services
```
BEEM_AFRICA_API_KEY=your_beem_api_key
BEEM_AFRICA_SECRET_KEY=your_beem_secret_key
AIRTEL_SMS_API_KEY=your_airtel_api_key
AIRTEL_SMS_SECRET_KEY=your_airtel_secret_key
```

#### Payment Gateway (DPO)
```
DPO_COMPANY_TOKEN=your_dpo_token
DPO_SERVICE_TYPE=your_service_type
DPO_PAYMENT_URL=https://secure.3gdirectpay.com/API/v6/
```

## Firebase Setup

1. Download your Firebase service account credentials JSON file
2. Place it in `storage/firebase/firebase_credentials.json`
3. Update `FIREBASE_PROJECT_ID` in your `.env` file

## File Structure

```
TempoAfrica/
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   └── Services/
├── resources/
│   ├── views/
│   ├── css/
│   └── js/
├── public/
├── storage/
└── routes/
```

## Security

- Never commit `.env` files to version control
- Keep Firebase credentials secure
- Use strong database passwords
- Regularly update dependencies

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## License

This project is proprietary software.

## Support

For support, please contact the development team.
