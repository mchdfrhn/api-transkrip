# User Management Endpoints

## Get All Users
- **Endpoint**: `GET /users`
- **Authentication**: Bearer Token (Admin only)
- **Description**: Get a list of all users

### Response
```json
[
    {
        "id": "uuid",
        "username": "username",
        "email": "user@example.com",
        "role": "user",
        "fullname": "Full Name",
        "url_photo": "https://example.com/photo.jpg"
    }
]
```

## Get User Profile
- **Endpoint**: `GET /users/{id}`
- **Authentication**: Bearer Token (Admin or Owner)
- **Description**: Get details of a specific user
- **Access**: 
  - Admins can view any profile
  - Users can only view their own profile

### Response
```json
{
    "id": "uuid",
    "username": "username",
    "email": "user@example.com",
    "role": "user",
    "fullname": "Full Name",
    "url_photo": "https://example.com/photo.jpg"
}
```

## Update User Profile
- **Endpoint**: `PUT /profile`
- **Authentication**: Bearer Token (User)
- **Description**: Update user's own profile

### Request
```json
{
    "username": "newusername",
    "email": "newemail@example.com",
    "phone": "1234567890",
    "url_photo": "https://example.com/newphoto.jpg"
}
```

### Response
```json
{
    "id": "uuid",
    "username": "newusername",
    "email": "newemail@example.com",
    "phone": "1234567890",
    "role": "user",
    "url_photo": "https://example.com/newphoto.jpg"
}
```

## Get User Dashboard
- **Endpoint**: `GET /dashboard`
- **Authentication**: Bearer Token (User)
- **Description**: Get user's request statistics

### Response
```json
{
    "total_requests": 10,
    "requests_accepted": 3,
    "requests_processing": 5,
    "requests_rejected": 2
}
```

### Error Responses
- `401 Unauthorized`: Invalid or missing token
- `403 Forbidden`: Insufficient permissions
- `404 Not Found`: User not found
- `422 Unprocessable Entity`: Invalid input format