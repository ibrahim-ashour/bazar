# Use an official PHP image as the base image
FROM php:7.3-fpm

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy the Lumen project into the container
COPY . /var/www/html

# Install required extensions and dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql

# Expose the port if your Lumen app uses one
# EXPOSE 8000

# Start your Lumen app
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
