# Project Title

Click Pack test project.

## Table of Contents

-   [Prerequisites](#prerequisites)
-   [Installation](#installation)


### Prerequisites

Need to Composer installed in your pc
PHP version needed 8.2


## Installation

```bash
git clone https://github.com/LikeAshraful/laravel-auth.git
cd laravel-auth
```

```bash
composer install
cp .env.example .env
php artisan key:generate
```

create a database and set database info to your .env file

```bash
php artisan migrate
php artisan serve
```

your project will serve at http://localhost:8000/


