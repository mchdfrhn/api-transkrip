# Notifications API Documentation

## Overview

API Notifications menyediakan endpoint untuk mengelola notifikasi pengguna. Endpoint ini memungkinkan pengguna untuk melihat, menandai sebagai dibaca, dan menghapus notifikasi.

## Endpoints

### 1. Get All Notifications

Mengambil semua notifikasi untuk pengguna yang terautentikasi.

```http
GET /api/notifications
```

#### Headers
```
Authorization: Bearer <access_token>
Accept: application/json
```

#### Response Success (200)
```json
{
    "status": "success",
    "message": "Notifications retrieved successfully",
    "data": [
        {
            "id": "notification-id",
            "user_id": "user-id",
            "message": "Your request has been processed",
            "response_id": "response-id",
            "is_read": false,
            "created_at": "2025-11-08T10:00:00Z",
            "updated_at": "2025-11-08T10:00:00Z"
        }
    ]
}
```

### 2. Get Unread Notifications Count

Mendapatkan jumlah notifikasi yang belum dibaca.

```http
GET /api/notifications/unread-count
```

#### Headers
```
Authorization: Bearer <access_token>
Accept: application/json
```

#### Response Success (200)
```json
{
    "status": "success",
    "message": "Unread notifications count retrieved successfully",
    "data": {
        "count": 5
    }
}
```

### 3. Mark Notification as Read

Menandai satu notifikasi sebagai sudah dibaca.

```http
POST /api/notifications/{id}/read
```

#### Parameters
| Name | Type | Description |
|------|------|-------------|
| id   | string | ID notifikasi yang akan ditandai sebagai dibaca |

#### Headers
```
Authorization: Bearer <access_token>
Accept: application/json
```

#### Response Success (200)
```json
{
    "status": "success",
    "message": "Notification marked as read",
    "data": {
        "id": "notification-id",
        "user_id": "user-id",
        "message": "Your request has been processed",
        "response_id": "response-id",
        "is_read": true,
        "created_at": "2025-11-08T10:00:00Z",
        "updated_at": "2025-11-08T10:00:00Z"
    }
}
```

### 4. Mark All Notifications as Read

Menandai semua notifikasi pengguna sebagai sudah dibaca.

```http
POST /api/notifications/mark-all-read
```

#### Headers
```
Authorization: Bearer <access_token>
Accept: application/json
```

#### Response Success (200)
```json
{
    "status": "success",
    "message": "All notifications marked as read",
    "data": null
}
```

### 5. Delete Notification

Menghapus satu notifikasi.

```http
DELETE /api/notifications/{id}
```

#### Parameters
| Name | Type | Description |
|------|------|-------------|
| id   | string | ID notifikasi yang akan dihapus |

#### Headers
```
Authorization: Bearer <access_token>
Accept: application/json
```

#### Response Success (200)
```json
{
    "status": "success",
    "message": "Notification deleted successfully",
    "data": null
}
```

### 6. Delete All Read Notifications

Menghapus semua notifikasi yang sudah dibaca.

```http
DELETE /api/notifications/read/all
```

#### Headers
```
Authorization: Bearer <access_token>
Accept: application/json
```

#### Response Success (200)
```json
{
    "status": "success",
    "message": "All read notifications deleted successfully",
    "data": null
}
```

## Error Responses

### Unauthorized (401)
```json
{
    "status": "error",
    "message": "Unauthorized"
}
```

### Forbidden (403)
```json
{
    "status": "error",
    "message": "You are not authorized to access this notification"
}
```

### Not Found (404)
```json
{
    "status": "error",
    "message": "Notification not found"
}
```

## Examples

### Get All Notifications

```bash
curl -X GET https://your-host/api/notifications \
    -H "Authorization: Bearer <access_token>" \
    -H "Accept: application/json"
```

### Mark Notification as Read

```bash
curl -X POST https://your-host/api/notifications/123/read \
    -H "Authorization: Bearer <access_token>" \
    -H "Accept: application/json"
```

### Delete All Read Notifications

```bash
curl -X DELETE https://your-host/api/notifications/read/all \
    -H "Authorization: Bearer <access_token>" \
    -H "Accept: application/json"
```

## Notes

1. Semua endpoint memerlukan autentikasi dengan token Bearer.
2. Pengguna hanya dapat mengakses notifikasi miliknya sendiri.
3. Response selalu mengandung field `status` dan `message`.
4. Field `data` bisa berisi object, array, atau null tergantung endpoint.
5. Timestamps (`created_at`, `updated_at`) menggunakan format ISO 8601.

## Related Endpoints
- [Authentication](./auth.md)
- [User Management](./users.md)
- [Response Management](./responses.md)