# Request Management Endpoints

## Create Request
- **Endpoint**: `POST /requests`
- **Authentication**: Bearer Token (User or Admin)
- **Description**: Create a new request

### Request
```json
{
    "user_id": "user-uuid",
    "type": "transkrip",
    "request": "Permintaan transkrip nilai"
}
```

### Response
```json
{
    "status": "success",
    "message": "Request created successfully",
    "data": {
        "id": "request-uuid",
        "user_id": "user-uuid",
        "type": "transkrip",
        "queue": "TRA251019-001",
        "request": "Permintaan transkrip nilai",
        "status": "pending",
        "created_at": "2025-10-19T10:00:00Z",
        "updated_at": "2025-10-19T10:00:00Z"
    }
}
```

## Get All Requests
- **Endpoint**: `GET /requests`
- **Authentication**: Bearer Token (Admin only)
- **Description**: Get list of all requests

### Response
```json
{
    "status": "success",
    "message": "Requests retrieved successfully",
    "data": [
        {
            "id": "request-uuid",
            "user_id": "user-uuid",
            "type": "transkrip",
            "queue": "TRA251019-001",
            "request": "Permintaan transkrip nilai",
            "status": "pending",
            "created_at": "2025-10-19T10:00:00Z",
            "updated_at": "2025-10-19T10:00:00Z"
        }
    ]
}
```

## Get Request by ID
- **Endpoint**: `GET /requests/{id}`
- **Authentication**: Bearer Token (Admin or Owner)
- **Description**: Get details of a specific request
- **Access**:
  - Admins can view any request
  - Users can only view their own requests

### Response
```json
{
    "status": "success",
    "message": "Request retrieved successfully",
    "data": {
        "id": "request-uuid",
        "user_id": "user-uuid",
        "type": "transkrip",
        "queue": "TRA251019-001",
        "request": "Permintaan transkrip nilai",
        "status": "pending",
        "created_at": "2025-10-19T10:00:00Z",
        "updated_at": "2025-10-19T10:00:00Z"
    }
}
```

## Queue Number Format
The queue number is automatically generated following this format:
- First 3 letters: Uppercase first 3 characters of the request type (e.g., TRA for transkrip)
- Next 6 digits: Current date (YYMMDD)
- Hyphen (-)
- Last 3 digits: Sequential number starting from 001, resets daily

Examples:
- `TRA251019-001`: First transkrip request on Oct 19, 2025
- `LEG251019-001`: First legalisir request on Oct 19, 2025

### Error Responses

#### 401 Unauthorized
```json
{
    "status": "error",
    "message": "Invalid or missing token"
}
```

#### 403 Forbidden
```json
{
    "status": "error",
    "message": "Insufficient permissions"
}
```

#### 404 Not Found
```json
{
    "status": "error",
    "message": "Request not found"
}
```

#### 422 Unprocessable Entity
```json
{
    "status": "error",
    "message": "Validation failed",
    "errors": {
        "user_id": ["The user id field is required"],
        "type": ["The type field must be one of: transkrip, legalisir"],
        "request": ["The request field is required"]
    }
}
```