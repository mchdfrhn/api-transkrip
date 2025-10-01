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

### 3. Mendapatkan Data Pengguna

- **Endpoint:** `GET /user`
- **Deskripsi:** Mendapatkan informasi pengguna yang sedang login.
- **Otentikasi:** Diperlukan.

---

## Endpoints Umum (Otentikasi Diperlukan)

### 4. Mendapatkan Profil Pengguna

- **Endpoint:** `GET /profile`
- **Deskripsi:** Mendapatkan halaman profil pengguna yang sedang login.
- **Akses Role:** `admin`, `user`

---

## Endpoints Khusus Admin (Otentikasi & Role Admin Diperlukan)

Semua endpoint di bawah ini memerlukan otentikasi dan hak akses sebagai `admin`.

### 5. Dashboard Admin

- **Endpoint:** `GET /admin/dashboard`
- **Deskripsi:** Mengakses halaman dashboard khusus admin.

### 6. Manajemen Pengguna (`/users`)

- **`GET /users`**: Mendapatkan daftar semua pengguna.
- **`POST /users`**: Membuat pengguna baru.
- **`GET /users/{id}`**: Menampilkan detail satu pengguna.
- **`PUT/PATCH /users/{id}`**: Memperbarui data pengguna.
- **`DELETE /users/{id}`**: Menghapus pengguna.

### 7. Manajemen Request (`/requests`)

- **`GET /requests`**: Mendapatkan daftar semua request.
- **`POST /requests`**: Membuat request baru.
- **`GET /requests/{id}`**: Menampilkan detail satu request.
- **`PUT/PATCH /requests/{id}`**: Memperbarui data request.
- **`DELETE /requests/{id}`**: Menghapus sebuah request.

### 8. Manajemen Response (`/responses`)

- **`GET /responses`**: Mendapatkan daftar semua response.
- **`POST /responses`**: Membuat response baru.
- **`GET /responses/{id}`**: Menampilkan detail satu response.
- **`PUT/PATCH /responses/{id}`**: Memperbarui data response.
-` **`DELETE /responses/{id}`**: Menghapus sebuah response.

### 9. Manajemen File Pengguna (`/user-files`)

- **`GET /user-files`**: Mendapatkan daftar semua file pengguna.
- **`POST /user-files`**: Mengunggah file pengguna baru.
- **`GET /user-files/{id}`**: Menampilkan detail satu file pengguna.
- **`PUT/PATCH /user-files/{id}`**: Memperbarui data file pengguna.
- **`DELETE /user-files/{id}`**: Menghapus sebuah file pengguna.

### 10. Manajemen File Request (`/request-files`)

- **`GET /request-files`**: Mendapatkan daftar semua file request.
- **`POST /request-files`**: Mengunggah file request baru.
- **`GET /request-files/{id}`**: Menampilkan detail satu file request.
- **`PUT/PATCH /request-files/{id}`**: Memperbarui data file request.
- **`DELETE /request-files/{id}`**: Menghapus sebuah file request.

### 11. Manajemen File Response (`/response-files`)

- **`GET /response-files`**: Mendapatkan daftar semua file response.
- **`POST /response-files`**: Mengunggah file response baru.
- **`GET /response-files/{id}`**: Menampilkan detail satu file response.
- **`PUT/PATCH /response-files/{id}`**: Memperbarui data file response.
- **`DELETE /response-files/{id}`**: Menghapus sebuah file response.
