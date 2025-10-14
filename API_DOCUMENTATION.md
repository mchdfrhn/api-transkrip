# API Documentation (Detail & Panduan Penggunaan)

Base URL: `/api`  
Semua endpoint di sini diasumsikan memerlukan header:
- `Authorization: Bearer <access_token>` (kecuali /login)
- `Accept: application/json`

Ringkasan singkat role:
- admin — akses penuh ke semua endpoint CRUD.
- user — terbatas:
  - Boleh membuat request (POST /requests) untuk dirinya sendiri.
  - Boleh membaca request by id miliknya (GET /requests/{id}).
  - Boleh membaca response by id jika response terkait dengan request miliknya (GET /responses/{id}).
  - Boleh mengunggah file request untuk request miliknya (POST /request-files).
  - Boleh membaca file request by id jika file terkait request miliknya (GET /request-files/{id}).
  - Boleh membaca file response by id jika file terkait response yang boleh diaksesnya (GET /response-files/{id}).

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

---

## Header Umum & Error
- Header: `Accept: application/json`, `Authorization: Bearer <token>`
- Error umum:
  - 401 Unauthorized — token tidak valid / tidak disertakan.
  - 403 Forbidden — role atau owner check gagal.
  - 404 Not Found — resource tidak ada / tidak boleh diakses.
  - 422 Unprocessable Entity — validasi input gagal.

Contoh response error:
```json
{ "message": "Unauthorized" }
```

---

## Roles & Permissions (Ringkas)
- admin: semua endpoint CRUD untuk users, requests, responses, file-entities.
- user:
  - requests: POST /requests, GET /requests/{id} (hanya milik sendiri)
  - responses: GET /responses/{id} (hanya jika response terkait request milik user)
  - request-files: POST /request-files (untuk request milik sendiri), GET /request-files/{id} (hanya file milik request user)
  - response-files: GET /response-files/{id} (hanya jika file terkait response yang dapat diakses user)

---

## Endpoint Detail

Catatan: semua contoh memakai JSON kecuali upload file (multipart/form-data).

### Users
- GET /users — admin
- POST /users — admin (body: name, email, password, role)
- GET /users/{id} — admin
- PUT/PATCH /users/{id} — admin
- DELETE /users/{id} — admin

### Profile
- GET /profile — admin, user (kembali data user yang login)

### Requests
- GET /requests — admin (mengambil semua request)
  - Contoh respons: `[ { "id":1, "user_id":2, "type":"...", "queue":1, "request":"..." }, ... ]`
- POST /requests — admin atau user
  - Admin bisa membuat request untuk siapa saja.
  - User hanya boleh membuat untuk dirinya sendiri.
  - Body contoh:
    ```json
    {
      "user_id": 2,
      "type": "transcription",
      "queue": 10,
      "request": "Audio file needs transcription"
    }
    ```
  - Respons sukses: 201 Created + request object
- GET /requests/{id} — admin atau owner (user hanya jika user_id === auth id)
- PUT/PATCH /requests/{id} — admin only
- DELETE /requests/{id} — admin only

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
      "status": "completed"
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

Jika butuh, saya bisa:
- Menambahkan contoh response lengkap per endpoint.
- Menyertakan skema JSON untuk model Request/Response/file.
- Menambahkan contoh implementasi client (JS / PHP / curl) untuk upload dan download file.
