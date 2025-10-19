# File Management Endpoints

## Upload Request File
- **Endpoint**: `POST /request-files`
- **Authentication**: Bearer Token (Admin or Request Owner)
- **Description**: Upload file(s) for a request

### Request
```
Content-Type: multipart/form-data

request_id: "request-uuid"
file: <file>
```

### Response
```json
{
    "id": "file-uuid",
    "request_id": "request-uuid",
    "filename": "document.pdf",
    "path": "storage/requests/document.pdf",
    "created_at": "2025-10-19T10:00:00Z",
    "updated_at": "2025-10-19T10:00:00Z"
}
```

## Upload Response File
- **Endpoint**: `POST /response-files`
- **Authentication**: Bearer Token (Admin only)
- **Description**: Upload file(s) for a response

### Request
```
Content-Type: multipart/form-data

response_id: "response-uuid"
file: <file>
```

### Response
```json
{
    "id": "file-uuid",
    "response_id": "response-uuid",
    "filename": "transcript.pdf",
    "path": "storage/responses/transcript.pdf",
    "created_at": "2025-10-19T10:00:00Z",
    "updated_at": "2025-10-19T10:00:00Z"
}
```

## Get Request File
- **Endpoint**: `GET /request-files/{id}`
- **Authentication**: Bearer Token (Admin or Request Owner)
- **Description**: Download or view a request file
- **Access**:
  - Admins can access any request file
  - Users can only access files from their requests

## Get Response File
- **Endpoint**: `GET /response-files/{id}`
- **Authentication**: Bearer Token (Admin or Request Owner)
- **Description**: Download or view a response file
- **Access**:
  - Admins can access any response file
  - Users can only access files from responses to their requests

### Supported File Types
- Documents: PDF, DOC, DOCX
- Images: JPG, PNG
- Maximum file size: 10MB

### Error Responses
- `401 Unauthorized`: Invalid or missing token
- `403 Forbidden`: Insufficient permissions
- `404 Not Found`: File not found
- `413 Payload Too Large`: File exceeds size limit
- `422 Unprocessable Entity`: Invalid input format