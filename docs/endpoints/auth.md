# Authentication Endpoints

## Login
- **Endpoint**: `POST /login`
- **Authentication**: No authentication required
- **Description**: Authenticate user and get access token

### Request
```json
{
    "email": "user@example.com",
    "password": "password"
}
```

### Response
```json
{
    "message": "Login success",
    "access_token": "1|xxxxxxxxxxxxxxxx",
    "token_type": "Bearer",
    "user": {
        "id": "uuid",
        "username": "username",
        "email": "user@example.com",
        "role": "user",
        "fullname": "Full Name",
        "url_photo": "https://example.com/photo.jpg"
    }
}
```

## Logout
- **Endpoint**: `POST /logout`
- **Authentication**: Bearer Token required
- **Description**: Invalidate the current access token

### Response
```json
{
    "message": "Logout success"
}
```

### Error Responses
- `401 Unauthorized`: Invalid credentials
- `422 Unprocessable Entity`: Invalid input format