# 🚀 Betterflow Task Management RESTful APIs
This repository contains two Laravel-based RESTful APIs for managing tasks, built for the Betterflow technical assessment.
## Setup Instructions
### 🔹1. Betterflow-Task-RESTfulAPIs (Standard Laravel App)
✅ Requirements:
<br>
PHP >= 8.1
<br>
Composer
<br>
MySQL / SQLite
<br>
Laravel CLI
<br>
### ⚙️ Steps to Run:
cd Betterflow-Task-RESTfulAPIs<br>
composer install<br>
cp .env.example .env<br>
php artisan key:generate<br>
php artisan migrate<br>
php artisan serve<br>
The API will be available at: http://127.0.0.1:8000
<br>
### 🔹 2. Containerized Betterflow-Task-RESTfulAPIs (Dockerized with API-KEY Middleware)
✅ Requirements:<br>
Docker<br>

Docker Compose<br>

### ⚙️ Steps to Run:<br>
cd "Containerized Betterflow-Task-RESTfulAPIs"<br>
docker-compose up -d --build<br>
Then run the Laravel setup inside the container:<br>
<br>
docker exec -it container_id bash<br>
composer install<br>
cp .env.example .env <br>
php artisan key:generate<br>
php artisan migrate<br>
exit<br>
### The API will be available at: http://localhost:9009

## 🔐 API-KEY (Only for Dockerized API) <br>
All requests must include a valid API key in the headers:
<br>
✅ Example Header:<br>
Authorization: X-API-KEY your_api_key_here<br>
API key already defined in the .env: <br>

API_KEY=your_api_key_here <br>
Middleware checks the key in every request to ensure secure access. <br>

📌 Example Endpoints (for both apps) <br>

## Method	URL	Description <br>
GET	/api/tasks	Get all tasks <br>
POST	/api/tasks	Create a new task <br>
GET	/api/tasks/{id}	Retrieve a task <br>
PUT	/api/tasks/{id}	Update a task <br>
DELETE	/api/tasks/{id}	Soft delete a task <br>
For the containerized version, include the API key with every request. <br>

### 🧪 Running Unit Tests  
<be>
Inside either project directory:<br>

php artisan test Or inside Docker:<br>

docker exec -it container_id  bash <br>
php artisan test<br>

## ✅ Best Practices Used
### ✅ Clean, modular controller logic

### ✅ Request validation & error handling

### ✅ Security: API key protection (Dockerized)

### ✅ Performance: pagination, indexed columns

### ✅ Readable code with comments

### ✅ Unit test coverage
