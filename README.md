# Tabdmin

💸 Yes. We have to drink. Yes. We have to pay. But we also have to put money on our Tab. This makes everyone's life, especially that of the treasurer, easier by automatically handling transactions to Zeus.

## Installation

### Requirements

- PHP 8.1 or higher
- Composer
- Node.js
- MySQL or MariaDB database

### Installation

1. Clone the repository
2. Run `composer install`
3. Run `npm install && npm run build`
4. Copy `.env.example` to `.env` and fill in the required values
    - `APP_KEY` can be generated with `php artisan key:generate`
    - `APP_ENV` should be set to `production` in production
    - `ZAUTH_` values can be retrieved from the [Zauth Clients page](https://adams.ugent.be/clients/)
    - `GOCARDLESS_` values can be retrieved from the [GoCardless Bank Account Data Developers Dashboard](https://bankaccountdata.gocardless.com/user-secrets/)
5. Run `php artisan migrate`
6. Set up the cronjob to run `php artisan schedule:run` every minute in the root of the project, therefore `crontab.example` can be used as a starting point

Instead of step 2, 3 and 5, the Dockerfile can be used to build a Docker image.


### Development

Use `php artisan serve` to start a development server.
For macOS users, [Laravel Valet](https://laravel.com/docs/10.x/valet) is highly recommended.

Use `npm run dev` to automatically recompile assets when they change.

See the [Laravel documentation](https://laravel.com/docs/10.x) for more information.
