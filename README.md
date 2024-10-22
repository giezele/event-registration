# Event Registration System

This project is a simple event registration system built with Symfony 7 and PHP 8.3. Following PSR-12.

Users can view available events, register for events.
The system handles form validation, CSRF protection, and database persistence using Doctrine ORM.

### Prerequisites

Ensure you have the following installed on your machine:

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

### Clone the Repository

First, clone the project from GitHub:

```bash
git clone https://github.com/giezele/event-registration
cd event-registration
```
### Environment Configuration

Duplicate the `.env.example` file to create a `.env` file:

```bash
cp .env.example .env
```

Make any necessary changes to the environment variables in the `.env` file. For instance, update database credentials or other configurations. By default, it will use MySQL.
```makefile
DATABASE_URL="mysql://symfony:symfony@db:3306/symfony?serverVersion=8.0"
```

### Docker Setup

Build and start the Docker containers using Docker Compose:

```bash
docker-compose up --build -d
```

This will build the Docker containers for the application and start them in the background.

### Install Dependencies

Once the containers are up, you need to install the PHP dependencies via Composer. This will also run the scrip to generate `APP_SECRET` to `.env` in order to ensure CSRF Protection

```bash
docker-compose exec php composer install
```

### Database Setup

After the dependencies are installed, you need to set up the database. Run the following commands to create the database and apply migrations:

```bash
# Create the database
docker-compose exec php bin/console doctrine:database:create

# Run the migrations
docker-compose exec php bin/console doctrine:migrations:migrate
```
(Optional) You can load sample data into the database using fixtures:
```bash
docker-compose exec php bin/console doctrine:fixtures:load
```
### Accessing the Application
After starting the Docker containers, you can access the application at:
```
http://localhost:8080
```

### Endpoints

* View All Events:
  * URL: http://localhost:8080/events
  * Description: Displays a list of available events with details (name, date, location, available spots).
  * Method: GET
  
* Create a New Event:
  * URL: http://localhost:8080/events/create
  * Description: Form to create a new event.
  * Method: GET (view form), POST (submit form)
  
* Register for an Event:
  * URL: http://localhost:8080/events/register/{id}
  * Description: Register for an event (replace {id} with the event ID).
  * Method: GET (view form), POST (submit form)

### Running Tests
To ensure everything is working properly, you can run the functional tests.

1. First, create the test database:
```bash
docker-compose exec php bin/console doctrine:database:create --env=test  --if-not-exists
docker-compose exec php bin/console doctrine:migrations:migrate --env=test --no-interaction
```
2. Run the tests using PHPUnit:
```bash
docker-compose exec php bin/phpunit
```

### Stopping the Containers

When you're done working with the project, you can stop the Docker containers with:

```bash
docker-compose down
```

### Common Commands

- **Access the PHP container**:
  ```bash
  docker-compose exec php bash
  ```

- **Clear the Symfony cache**:
  ```bash
  docker-compose exec php bin/console cache:clear
  ```

- **Run database migrations**:
  ```bash
  docker-compose exec php bin/console doctrine:migrations:migrate
  ```
