# Response Management Endpoints

## Create Response
- **Endpoint**: `POST /responses`
- **Authentication**: Bearer Token (Admin only)
- **Description**: Create a response for a request

### Request
```json
{
    "request_id": "request-uuid",
    "response": "Response content"
}
```

### Response
```json
{
    "status": "success",
    "message": "Response created successfully",
    "data": {
        "id": "response-uuid",
        "request_id": "request-uuid",
        "response": "Response content",
        "created_at": "2025-10-19T10:00:00Z",
        "updated_at": "2025-10-19T10:00:00Z"
    }
}
```

## Get All Responses
- **Endpoint**: `GET /responses`
- **Authentication**: Bearer Token (Admin only)
- **Description**: Get list of all responses

### Response
```json
{
    "status": "success",
    "message": "Responses retrieved successfully",
    "data": [
        {
            "id": "response-uuid",
            "request_id": "request-uuid",
            "response": "Response content",
            "created_at": "2025-10-19T10:00:00Z",
            "updated_at": "2025-10-19T10:00:00Z"
        }
    ]
}
```

## Get Response by ID
- **Endpoint**: `GET /responses/{id}`
- **Authentication**: Bearer Token (Admin or Request Owner)
- **Description**: Get details of a specific response
- **Access**:
  - Admins can view any response
  - Users can only view responses to their requests

### Response
```json
{
    "status": "success",
    "message": "Response retrieved successfully",
    "data": {
        "id": "response-uuid",
        "request_id": "request-uuid",
        "response": "Response content",
        "created_at": "2025-10-19T10:00:00Z",
        "updated_at": "2025-10-19T10:00:00Z"
    }
}
```

## Response Status Types
Available status values:
- `accepted`: Request has been approved
- `rejected`: Request has been rejected
- `processing`: Request is being processed

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
    "message": "Response not found"
}
```

#### 422 Unprocessable Entity
```json
{
    "status": "error",
    "message": "Validation failed",
    "errors": {
        "request_id": ["The request id field is required"],
        "response": ["The response field is required"]
    }
}
```