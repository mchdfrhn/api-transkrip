# API Documentation

Dokumentasi ini menjelaskan endpoint yang tersedia di API.

**Base URL:** `/api`

---

## Otentikasi

Otentikasi menggunakan Bearer Token yang didapat dari endpoint `POST /login`. Untuk semua endpoint yang memerlukan otentikasi, sertakan header berikut:

```
Authorization: Bearer <your_access_token>
Accept: application/json
```

---

## Endpoints Otentikasi

### 1. Login Pengguna

- **Endpoint:** `POST /login`
- **Deskripsi:** Mengotentikasi pengguna dan mengembalikan access token.
- **Request Body:**
  ```json
  {
    "email": "user@example.com",
    "password": "password"
  }
  ```
- **Respons Sukses (200 OK):**
  ```json
  {
    "message": "Login success",
    "access_token": "1|xxxxxxxxxxxxxxxxxxxxxxxx",
    "token_type": "Bearer",
    "user": { ... }
  }
  ```

### 2. Logout Pengguna

- **Endpoint:** `POST /logout`
- **Deskripsi:** Mencabut access token pengguna.
- **Otentikasi:** Diperlukan.
- **Respons Sukses (200 OK):**
  ```json
  {
    "message": "Logout success"
  }
  ```

### 3. Mendapatkan Data Pengguna

- **Endpoint:** `GET /users`
- **Deskripsi:** Mendapatkan informasi pengguna yang sedang login.
- **Otentikasi:** Diperlukan.
- **Respons Sukses (200 OK):**
  ```json
  {
    "id": 1,
    "name": "User Name",
    "email": "user@example.com",
    "email_verified_at": null,
    "role": "user",
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-01T00:00:00.000000Z"
  }
  ```

---

## Endpoints Umum (Otentikasi Diperlukan)

### 4. Mendapatkan Profil Pengguna

- **Endpoint:** `GET /profile`
- **Deskripsi:** Mendapatkan halaman profil pengguna yang sedang login.
- **Akses Role:** `admin`, `user`
- **Respons Sukses (200 OK):**
  ```json
  {
    "message": "This is your profile",
    "user": { ... }
  }
  ```

---

## Endpoints Khusus Admin (Otentikasi & Role Admin Diperlukan)

Semua endpoint di bawah ini memerlukan otentikasi dan hak akses sebagai `admin`.

### 5. Dashboard Admin

- **Endpoint:** `GET /admin/dashboard`
- **Deskripsi:** Mengakses halaman dashboard khusus admin.
- **Respons Sukses (200 OK):**
  ```json
  {
    "message": "Welcome, Admin!"
  }
  ```

### 6. Manajemen Pengguna (`/users`)

- **`GET /users`**: Mendapatkan daftar semua pengguna.
  - **Respons Sukses (200 OK):** `[{...}, {...}]` (Array of user objects)
- **`POST /users`**: Membuat pengguna baru.
  - **Request Body:**
    ```json
    {
      "name": "New User",
      "email": "new@example.com",
      "password": "password",
      "password_confirmation": "password",
      "role": "user"
    }
    ```
  - **Respons Sukses (201 Created):** `{...}` (User object yang baru dibuat)
- **`GET /users/{id}`**: Menampilkan detail satu pengguna.
  - **Respons Sukses (200 OK):** `{...}` (User object)
- **`PUT/PATCH /users/{id}`**: Memperbarui data pengguna.
  - **Request Body:**
    ```json
    {
      "name": "Updated Name",
      "email": "updated@example.com",
      "role": "admin"
    }
    ```
  - **Respons Sukses (200 OK):** `{...}` (User object yang telah diperbarui)
- **`DELETE /users/{id}`**: Menghapus pengguna.
  - **Respons Sukses (204 No Content):** (Tidak ada body)

### 7. Manajemen Request (`/requests`)

- **`GET /requests`**: Mendapatkan daftar semua request.
  - **Respons Sukses (200 OK):** `[{...}, {...}]` (Array of request objects)
- **`POST /requests`**: Membuat request baru.
  - **Request Body:**
    ```json
    {
      "user_id": 1,
      "title": "Judul Request",
      "description": "Deskripsi detail dari request."
    }
    ```
  - **Respons Sukses (201 Created):** `{...}` (Request object yang baru dibuat)
- **`GET /requests/{id}`**: Menampilkan detail satu request.
  - **Respons Sukses (200 OK):** `{...}` (Request object)
- **`PUT/PATCH /requests/{id}`**: Memperbarui data request.
  - **Request Body:**
    ```json
    {
      "title": "Judul Request Diperbarui",
      "description": "Deskripsi detail dari request yang diperbarui."
    }
    ```
  - **Respons Sukses (200 OK):** `{...}` (Request object yang telah diperbarui)
- **`DELETE /requests/{id}`**: Menghapus sebuah request.
  - **Respons Sukses (204 No Content):** (Tidak ada body)

### 8. Manajemen Response (`/responses`)

- **`GET /responses`**: Mendapatkan daftar semua response.
  - **Respons Sukses (200 OK):** `[{...}, {...}]` (Array of response objects)
- **`POST /responses`**: Membuat response baru.
  - **Request Body:**
    ```json
    {
      "request_id": 1,
      "content": "Ini adalah isi dari response."
    }
    ```
  - **Respons Sukses (201 Created):** `{...}` (Response object yang baru dibuat)
- **`GET /responses/{id}`**: Menampilkan detail satu response.
  - **Respons Sukses (200 OK):** `{...}` (Response object)
- **`PUT/PATCH /responses/{id}`**: Memperbarui data response.
  - **Request Body:**
    ```json
    {
      "content": "Isi dari response yang telah diperbarui."
    }
    ```
  - **Respons Sukses (200 OK):** `{...}` (Response object yang telah diperbarui)
- **`DELETE /responses/{id}`**: Menghapus sebuah response.
  - **Respons Sukses (204 No Content):** (Tidak ada body)

### 9. Manajemen File Pengguna (`/user-files`)

- **`GET /user-files`**: Mendapatkan daftar semua file pengguna.
  - **Respons Sukses (200 OK):** `[{...}, {...}]` (Array of file objects)
- **`POST /user-files`**: Mengunggah file pengguna baru.
  - **Request Body:** `multipart/form-data`
    - `user_id`: (integer) ID pengguna
    - `file`: (file) File yang akan diunggah
  - **Respons Sukses (201 Created):** `{...}` (File object yang baru dibuat)
- **`GET /user-files/{id}`**: Menampilkan detail satu file pengguna.
  - **Respons Sukses (200 OK):** `{...}` (File object)
- **`PUT/PATCH /user-files/{id}`**: Memperbarui data file pengguna.
  - **Catatan:** Endpoint ini biasanya tidak digunakan untuk mengganti file, lebih baik hapus dan unggah yang baru. Jika untuk mengubah metadata:
  - **Request Body:**
    ```json
    {
      "user_id": 2
    }
    ```
  - **Respons Sukses (200 OK):** `{...}` (File object yang telah diperbarui)
- **`DELETE /user-files/{id}`**: Menghapus sebuah file pengguna.
  - **Respons Sukses (204 No Content):** (Tidak ada body)

### 10. Manajemen File Request (`/request-files`)

- **`GET /request-files`**: Mendapatkan daftar semua file request.
  - **Respons Sukses (200 OK):** `[{...}, {...}]` (Array of file objects)
- **`POST /request-files`**: Mengunggah file request baru.
  - **Request Body:** `multipart/form-data`
    - `request_id`: (integer) ID request
    - `file`: (file) File yang akan diunggah
  - **Respons Sukses (201 Created):** `{...}` (File object yang baru dibuat)
- **`GET /request-files/{id}`**: Menampilkan detail satu file request.
  - **Respons Sukses (200 OK):** `{...}` (File object)
- **`PUT/PATCH /request-files/{id}`**: Memperbarui data file request.
  - **Request Body:**
    ```json
    {
      "request_id": 2
    }
    ```
  - **Respons Sukses (200 OK):** `{...}` (File object yang telah diperbarui)
- **`DELETE /request-files/{id}`**: Menghapus sebuah file request.
  - **Respons Sukses (204 No Content):** (Tidak ada body)

### 11. Manajemen File Response (`/response-files`)

- **`GET /response-files`**: Mendapatkan daftar semua file response.
  - **Respons Sukses (200 OK):** `[{...}, {...}]` (Array of file objects)
- **`POST /response-files`**: Mengunggah file response baru.
  - **Request Body:** `multipart/form-data`
    - `response_id`: (integer) ID response
    - `file`: (file) File yang akan diunggah
  - **Respons Sukses (201 Created):** `{...}` (File object yang baru dibuat)
- **`GET /response-files/{id}`**: Menampilkan detail satu file response.
  - **Respons Sukses (200 OK):** `{...}` (File object)
- **`PUT/PATCH /response-files/{id}`**: Memperbarui data file response.
  - **Request Body:**
    ```json
    {
      "response_id": 2
    }
    ```
  - **Respons Sukses (200 OK):** `{...}` (File object yang telah diperbarui)
- **`DELETE /response-files/{id}`**: Menghapus sebuah file response.
  - **Respons Sukses (204 No Content):** (Tidak ada body)
