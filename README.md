# QR Code Microservice

This repository provides an example of setting up a QR code-generating microservice using the following technologies:

- **[Dumbo](https://github.com/notrab/dumbo)** as the HTTP router.
- **[FrankenPHP](https://frankenphp.dev/)** to serve the application in both development and production environments.
- **[Pest](https://pestphp.com/)** for testing.
- **[Fly.io](https://fly.io/docs/)** for deployment.

> **Note**: This project is intended as a demonstration and should not be used in production without further customization and testing.

## API Documentation

### Example application

[https://dumbo-qr.fly.dev](https://dumbo-qr.fly.dev )

### Endpoint: `POST /`

#### Parameters:
- **`data`** (string, required): The data to be encoded.
- **`scale`** (integer, optional): The scale of the output.
- **`format`** (string, optional): The desired output format. Supported values:
  - `svg`, `html`, `bmp`, `gif`, `jpg`, `png`, `webp`, `text`, `json`

#### Response:
The response will be based on the specified format, returning either an image or text representation of the data.

## Prerequisites

Before you begin, ensure you have the following:

- [Docker](https://www.docker.com/) installed on your system.
- A [Fly.io](https://fly.io/) account and the Fly.io CLI installed and configured.

## Getting Started

### 1. Install Dependencies

Before building the Docker images, install the project dependencies using Composer:

```bash
composer install
```

### 2. Build and Start Docker Containers

#### Development Environment

To build and start the Docker containers for development, you can use the provided utility functions in the `Makefile`:

1. **Build the Docker images**:

   ```bash
   // docker-compose -f docker-compose.yml build
   make build
   ```

2. **Start the Docker containers**:

   ```bash
   // docker-compose up -d --build app
   make start
   ```

3. Access the application in your browser at [https://localhost](https://localhost).

### 3. Deploying to Fly.io

To deploy your application to Fly.io, follow these steps:

**Initial Deployment:**

If you have not deployed the application before, you need to create an application instance on Fly.io:

1. **Create the application instance and deploy**:

   ```bash
   // fly launch
   make launch
   ```

**Subsequent Deployments:**

For future deployments, simply run:

   ```bash
   // fly deploy
   make deploy
   ```

## Further Documentation

For more detailed information about the technologies used in this example, please refer to their official documentation:

- [Dumbo Documentation](https://github.com/notrab/dumbo)
- [FrankenPHP Documentation](https://frankenphp.dev/)
- [Pest Documentation](https://pestphp.com/)
- [Fly.io Documentation](https://fly.io/docs/)
