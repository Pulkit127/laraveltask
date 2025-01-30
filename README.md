#Laravel Task Application

This is a Laravel-based application named Laravel-Task. Below are the steps to set up and configure the application.

## Prerequisites

- PHP >= 8.2
- Composer
- MySQL

## Installation

### Step 1: Clone the repository

<pre>
<code>[git clone https://github.com/Pulkit127/laraveltask.git] && 
cd laraveltask
</code>

</pre>

### Step 2: Install Composer dependencies

<pre>
<code>composer install</code>
</pre>

### Step 3: Set up the environment configuration

<pre>
<code>cp .env.example .env</code>
</pre>

Update the `.env` file with the following:

<pre>
<code>APP_NAME=Laravel-Task
APP_ENV=local
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=http://localhost
</code>
</pre>

### Database Configuration

Ensure the database settings are properly configured in the `.env` file:

<pre>
<code>DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
</code>
</pre>

### Step 4: Generate application key

<pre>
<code>php artisan key:generate</code>
</pre>

### Step 5: Run migrations

<pre>
<code>php artisan migrate</code>
</pre>

## Running the Application

Start the Laravel development server:

<pre>
<code>php artisan serve</code>
</pre>

The application will be accessible at [http://localhost:8000](http://localhost:8000).

