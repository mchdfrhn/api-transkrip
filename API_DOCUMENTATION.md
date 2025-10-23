# API Documentation

## Overview
This API provides endpoints for managing transcript and document requests in an academic system. It supports user authentication, request management, response handling, and file uploads/downloads.

## Table of Contents
1. [Authentication](./docs/endpoints/auth.md)
   - Login
   - Logout
2. [User Management](./docs/endpoints/users.md)
   - User Profile
   - Dashboard Statistics
3. [Request Management](./docs/endpoints/requests.md)
   - Create Request
   - View Requests
   - Queue System
   - Status Types
4. [Response Management](./docs/endpoints/responses.md)
   - Create Response
   - View Responses
5. [File Management](./docs/endpoints/files.md)
   - Upload Files
   - Download Files
   - Supported Formats

## Base URL
```
/api
```

## Global Headers
All endpoints require these headers (except /login):
```
Authorization: Bearer <access_token>
Accept: application/json
```

## Role-Based Access Control
The API implements role-based access control with two main roles:

### Admin Role
- Full access to all CRUD operations
- Can manage all users, requests, and responses
- Can upload and download all files

### User Role
- Limited access to specific operations:
  - Create requests for themselves
  - View their own requests
  - View responses to their requests
  - Upload files for their requests
  - Download files related to their requests/responses
  - Update their own profile
  - View their dashboard statistics

---

## Autentikasi

1. Login
- Endpoint: `POST /login`
- Body:
  ```json
  {
    "email": "user@example.com",
    "password": "password"
  }
  ```
- Respons sukses (200):
  ```json
  {
    "message": "Login success",
    "access_token": "1|xxxxxxxxxxxxxxxx",
    "token_type": "Bearer",
    "user": { "id": 1, "name": "...", "role": "user" }
  }
  ```
- Contoh curl:
  ```
  curl -X POST -H "Accept: application/json" \
    -d "email=user@example.com&password=password" \
    https://your-host/api/login
  ```

2. Logout
- Endpoint: `POST /logout`
- Auth required.
- Respons sukses: `{ "message": "Logout success" }`

## Error Responses
All error responses follow a consistent format:

```json
{
    "message": "Error description",
    "code": 400
}
```

Common error codes:
- `401 Unauthorized`: Invalid or missing authentication token
- `403 Forbidden`: Insufficient permissions or ownership validation failed
- `404 Not Found`: Requested resource does not exist
- `422 Unprocessable Entity`: Input validation failed
- `429 Too Many Requests`: Rate limit exceeded
- `500 Internal Server Error`: Server-side error

---

## Roles & Permissions (Ringkas)
- admin: semua endpoint CRUD untuk users, requests, responses, file-entities.
- user:
  - requests: POST /requests, GET /requests/{id} (hanya milik sendiri)
  - responses: GET /responses/{id} (hanya jika response terkait request milik user)
  - request-files: POST /request-files (untuk request milik sendiri), GET /request-files/{id} (hanya file milik request user)
  - response-files: GET /response-files/{id} (hanya jika file terkait response yang dapat diakses user)

## API Versioning
The current API version is v1. All endpoints are prefixed with `/api`.

## Rate Limiting
- 60 requests per minute per user
- Rate limit headers are included in all responses:
  ```
  X-RateLimit-Limit: 60
  X-RateLimit-Remaining: 59
  X-RateLimit-Reset: 1635519600
  ```

## Pagination
List endpoints support pagination with the following query parameters:
- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 15, max: 100)

Example response format:
```json
{
    "data": [...],
    "meta": {request
        "current_page": 1,
        "last_page": 10,
        "total": 150,
        "per_page": 15
    }
}
```

## For More Details
Please refer to the specific documentation sections linked in the Table of Contents above.

Contoh curl (user membuat request untuk dirinya sendiri):
```
curl -X POST https://your-host/api/requests \
  -H "Authorization: Bearer <token>" \
  -H "Accept: application/json" \
  -d '{"user_id": 5, "type":"transcription","queue":1,"request":"..."}'
```

### Responses
- GET /responses — admin only
- POST /responses — admin only
  - Body contoh:
    ```json
    {
      "request_id": 1,
      "response": "Hasil transkrip ...",
    }
    ```
- GET /responses/{id} — admin atau owner dari request terkait
  - User hanya boleh membaca jika response.request.user_id === auth id
- PUT/PATCH /responses/{id} — admin only
- DELETE /responses/{id} — admin only

Contoh curl (user membaca response tertentu):
```
curl -H "Authorization: Bearer <token>" \
  -H "Accept: application/json" \
  https://your-host/api/responses/123
```
- Jika bukan admin dan response tidak terkait request milik user → 403 Forbidden.

---

## File Endpoints (Upload / Download / Metadata)

Semua file endpoint memakai multipart/form-data untuk upload.

### User Files (`/user-files`)
- GET /user-files — admin (list semua)
- POST /user-files — admin (upload file atas nama user) — body: `user_id`, `file`
- GET /user-files/{id} — admin atau owner (file.user_id === auth id)
- PUT/PATCH /user-files/{id} — admin (ubah metadata)
- DELETE /user-files/{id} — admin

Contoh upload:
```
curl -X POST https://your-host/api/user-files \
  -H "Authorization: Bearer <token>" \
  -F "user_id=5" -F "file=@/path/to/file.mp3"
```

### Request Files (`/request-files`)
- GET /request-files — admin
- POST /request-files — admin atau user (user hanya untuk request yang dimiliki)
  - Body: `request_id`, `file`
- GET /request-files/{id} — admin atau owner of the related request
- PUT/PATCH /request-files/{id} — admin
- DELETE /request-files/{id} — admin

Contoh upload (user mengunggah file untuk request miliknya):
```
curl -X POST https://your-host/api/request-files \
  -H "Authorization: Bearer <token>" \
  -F "request_id=7" -F "file=@/home/user/audio.wav"
```

### Response Files (`/response-files`)
- GET /response-files — admin
- POST /response-files — admin (biasanya admin yang mengunggah hasil/attachment)
  - Body: `response_id`, `file`
- GET /response-files/{id} — admin atau user yang boleh mengakses response terkait
- PUT/PATCH /response-files/{id} — admin
- DELETE /response-files/{id} — admin

Contoh download file response (user):
```
curl -H "Authorization: Bearer <token>" \
  https://your-host/api/response-files/55 --output hasil.zip
```

---

## Contoh Alur (User)
1. Login → dapat token.
2. POST /requests (user_id harus sama dengan id token) → buat permintaan.
3. POST /request-files (attach file ke request yang dibuat).
4. Cek status: GET /requests/{id}.
5. Ketika admin membuat response: user bisa GET /responses/{response_id} dan GET /response-files/{file_id} jika tersedia.

## Contoh Alur (Admin)
1. Login sebagai admin.
2. GET /requests → lihat seluruh antrian.
3. POST /responses → buat hasil untuk request tertentu.
4. POST /response-files → upload file hasil.
5. CRUD penuh untuk semua resources.

---

## Best Practices
- Selalu sertakan header Accept dan Authorization.
- Untuk user, pastikan `user_id` di body sesuai dengan token yang digunakan (server akan menolak jika tidak sama).
- Gunakan status code yang benar: 201 untuk create, 204 untuk delete tanpa body, 200 untuk sukses baca/update.
- Tangani error 403/401 pada client: tampilkan pesan sesuai respons API.

---