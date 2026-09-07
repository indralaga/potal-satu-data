# API Documentation - Satu Data TAPUT

## Base URL
```
http://localhost:8000/api
```

## Authentication

Gunakan Bearer Token dalam Authorization header:

```
Authorization: Bearer YOUR_TOKEN_HERE
```

## Endpoints

### Authentication

#### Login
```http
POST /login
Content-Type: application/json

{
  "email": "admin@taput.gov.id",
  "password": "password123"
}

Response (200):
{
  "message": "Login berhasil",
  "user": {
    "id": 1,
    "name": "Admin Portal",
    "email": "admin@taput.gov.id",
    "role": "admin_portal",
    "opd_id": null,
    "is_active": true
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

#### Register
```http
POST /register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "opd_id": 1
}

Response (201):
{
  "message": "Registrasi berhasil. Tunggu persetujuan admin.",
  "user": { ... }
}
```

#### Logout
```http
POST /logout
Authorization: Bearer TOKEN

Response (200):
{
  "message": "Logout berhasil"
}
```

#### Get Current User
```http
GET /user
Authorization: Bearer TOKEN

Response (200):
{
  "id": 1,
  "name": "Admin Portal",
  "email": "admin@taput.gov.id",
  "role": "admin_portal",
  "is_active": true
}
```

### Dashboard

#### Get Dashboard Statistics
```http
GET /dashboard
Authorization: Bearer TOKEN

Response (200):
{
  "stats": {
    "total_datasets": 10,
    "total_records": 5000,
    "total_opds": 5,
    "total_users": 15
  },
  "recent_datasets": [
    {
      "id": 1,
      "name": "Data Kesehatan 2024",
      "category": "Kesehatan",
      "opd": { "id": 1, "name": "Dinas Kesehatan" },
      "row_count": 500,
      "created_at": "2024-01-15T10:30:00Z"
    }
  ]
}
```

### Datasets

#### List Datasets
```http
GET /datasets?search=kesehatan&category=Kesehatan&per_page=15&page=1
Authorization: Bearer TOKEN

Query Parameters:
- search: string (optional) - Search by name or description
- category: string (optional) - Filter by category
- per_page: number (default: 15)
- page: number (default: 1)

Response (200):
{
  "data": [
    {
      "id": 1,
      "name": "Data Kesehatan 2024",
      "description": "Data kesehatan kabupaten tahun 2024",
      "category": "Kesehatan",
      "user": { "id": 2, "name": "Admin DINKES" },
      "opd": { "id": 1, "name": "Dinas Kesehatan" },
      "is_public": true,
      "row_count": 500,
      "file_size": 102400,
      "published_at": "2024-01-15T10:30:00Z",
      "files": [
        {
          "id": 1,
          "file_name": "1704869400_data.csv",
          "file_type": "csv",
          "file_size": 102400,
          "column_headers": ["id", "nama", "usia", "diagnosis"]
        }
      ],
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z"
    }
  ],
  "pagination": {
    "total": 10,
    "per_page": 15,
    "current_page": 1,
    "last_page": 1,
    "from": 1,
    "to": 10
  }
}
```

#### Upload Dataset
```http
POST /datasets
Authorization: Bearer TOKEN
Content-Type: multipart/form-data

Form Data:
- name: "Data Kesehatan 2024" (required)
- description: "Deskripsi dataset" (required)
- category: "Kesehatan" (required)
- file: <file.csv|file.xlsx|file.json> (required)
- is_public: true/false (optional, default: false)

Response (201):
{
  "message": "Dataset berhasil diupload",
  "dataset": {
    "id": 1,
    "name": "Data Kesehatan 2024",
    "description": "Deskripsi dataset",
    "category": "Kesehatan",
    "row_count": 500,
    "file_size": 102400,
    "files": [ ... ]
  }
}
```

#### Get Dataset Detail
```http
GET /datasets/{id}
Authorization: Bearer TOKEN

Response (200):
{
  "id": 1,
  "name": "Data Kesehatan 2024",
  "description": "Deskripsi dataset",
  "category": "Kesehatan",
  "user": { ... },
  "opd": { ... },
  "is_public": true,
  "row_count": 500,
  "file_size": 102400,
  "files": [ ... ]
}
```

#### Update Dataset
```http
PUT /datasets/{id}
Authorization: Bearer TOKEN
Content-Type: application/json

{
  "name": "Data Kesehatan 2024 (Updated)",
  "description": "Deskripsi baru",
  "category": "Kesehatan",
  "is_public": true
}

Response (200):
{
  "message": "Dataset berhasil diupdate",
  "dataset": { ... }
}
```

#### Delete Dataset
```http
DELETE /datasets/{id}
Authorization: Bearer TOKEN

Response (200):
{
  "message": "Dataset berhasil dihapus"
}
```

#### Download Dataset
```http
POST /datasets/{id}/download
Authorization: Bearer TOKEN

Response: File binary (CSV/Excel/JSON)
```

### Users (Admin Portal Only)

#### List Users
```http
GET /users?search=admin&role=admin_opd&is_active=1&per_page=15&page=1
Authorization: Bearer TOKEN

Query Parameters:
- search: string (optional) - Search by name or email
- role: string (optional) - Filter by role
- is_active: 0|1 (optional)
- per_page: number (default: 15)
- page: number (default: 1)

Response (200):
{
  "data": [
    {
      "id": 2,
      "name": "Admin DINKES",
      "email": "admin.dinas.kesehatan@taput.gov.id",
      "role": "admin_opd",
      "opd": { "id": 1, "name": "Dinas Kesehatan" },
      "is_active": true,
      "created_at": "2024-01-15T10:30:00Z"
    }
  ],
  "pagination": { ... }
}
```

#### Get User Detail
```http
GET /users/{id}
Authorization: Bearer TOKEN

Response (200):
{
  "id": 2,
  "name": "Admin DINKES",
  "email": "admin.dinas.kesehatan@taput.gov.id",
  "role": "admin_opd",
  "opd": { ... },
  "is_active": true
}
```

#### Update User
```http
PUT /users/{id}
Authorization: Bearer TOKEN
Content-Type: application/json

{
  "name": "Admin Kesehatan Baru",
  "email": "admin.baru@taput.gov.id",
  "password": "new_password123",
  "role": "admin_opd",
  "is_active": true
}

Response (200):
{
  "message": "User berhasil diupdate",
  "user": { ... }
}
```

#### Delete User
```http
DELETE /users/{id}
Authorization: Bearer TOKEN

Response (200):
{
  "message": "User berhasil dihapus"
}
```

#### Approve Pending User
```http
POST /users/{id}/approve
Authorization: Bearer TOKEN

Response (200):
{
  "message": "User berhasil diaktifkan",
  "user": { ... }
}
```

## Error Responses

### Validation Error (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

### Unauthorized (401)
```json
{
  "message": "Unauthenticated."
}
```

### Forbidden (403)
```json
{
  "message": "Unauthorized"
}
```

### Not Found (404)
```json
{
  "message": "Not found"
}
```

### Server Error (500)
```json
{
  "message": "Server error occurred"
}
```

## Rate Limiting

Current implementation doesn't have rate limiting. Add later if needed.

## Pagination

Most list endpoints support pagination:

```
GET /api/datasets?per_page=20&page=1
```

Response includes:
```json
{
  "data": [ ... ],
  "pagination": {
    "total": 100,
    "per_page": 20,
    "current_page": 1,
    "last_page": 5,
    "from": 1,
    "to": 20
  }
}
```
