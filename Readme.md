# 🚀 Betterflow Task Management RESTful APIs
This repository contains two Laravel-based RESTful APIs for managing tasks, built for the Betterflow technical assessment.
## Setup Instructions
### 🔹1. Betterflow-Task-RESTfulAPIs (Standard Laravel App)
✅ Requirements:
PHP >= 8.1

Composer

MySQL / SQLite

Laravel CLI

### ⚙️ Steps to Run:
cd Betterflow-Task-RESTfulAPIs
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
The API will be available at: http://127.0.0.1:8000

### 🔹 2. Containerized Betterflow-Task-RESTfulAPIs (Dockerized with API-KEY Middleware)
✅ Requirements:
Docker

Docker Compose

### ⚙️ Steps to Run:
cd "Containerized Betterflow-Task-RESTfulAPIs"
docker-compose up -d --build
Then run the Laravel setup inside the container:

docker exec -it container_id bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
exit
### The API will be available at: http://localhost:9009

## 🔐 API-KEY (Only for Dockerized API)
All requests must include a valid API key in the headers:

✅ Example Header:
Authorization: X-API-KEY your_api_key_here
API key already defined in the .env:

API_KEY=your_api_key_here
Middleware checks the key in every request to ensure secure access.

📌 Example Endpoints (for both apps)

## Method	URL	Description
GET	/api/tasks	Get all tasks
POST	/api/tasks	Create a new task
GET	/api/tasks/{id}	Retrieve a task
PUT	/api/tasks/{id}	Update a task
DELETE	/api/tasks/{id}	Soft delete a task
For the containerized version, include the API key with every request.

### 🧪 Running Unit Tests
Inside either project directory:

php artisan test Or inside Docker:

docker exec -it container_id  bash
php artisan test

## ✅ Best Practices Used
### ✅ Clean, modular controller logic

### ✅ Request validation & error handling

### ✅ Security: API key protection (Dockerized)

### ✅ Performance: pagination, indexed columns

### ✅ Readable code with comments

### ✅ Unit test coverage
