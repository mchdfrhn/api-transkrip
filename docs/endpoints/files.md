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
    "status": "success",
    "message": "File uploaded successfully",
    "data": {
        "id": "file-uuid",
        "request_id": "request-uuid",
        "filename": "document.pdf",
        "path": "storage/requests/document.pdf",
        "created_at": "2025-10-19T10:00:00Z",
        "updated_at": "2025-10-19T10:00:00Z"
    }
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
    "status": "success",
    "message": "File uploaded successfully",
    "data": {
        "id": "file-uuid",
        "response_id": "response-uuid",
        "filename": "transcript.pdf",
        "path": "storage/responses/transcript.pdf",
        "created_at": "2025-10-19T10:00:00Z",
        "updated_at": "2025-10-19T10:00:00Z"
    }
}
```

## Get Request File
- **Endpoint**: `GET /request-files/{id}`
- **Authentication**: Bearer Token (Admin or Request Owner)
- **Description**: Download or view a request file
- **Access**:
  - Admins can access any request file
  - Users can only access files from their requests

### Response
For files that can be viewed (images, PDFs):
```json
{
    "status": "success",
    "message": "File retrieved successfully",
    "data": {
        "id": "file-uuid",
        "request_id": "request-uuid",
        "filename": "document.pdf",
        "path": "storage/requests/document.pdf",
        "url": "http://example.com/storage/requests/document.pdf",
        "mime_type": "application/pdf",
        "size": 1048576,
        "created_at": "2025-10-19T10:00:00Z",
        "updated_at": "2025-10-19T10:00:00Z"
    }
}
```

For direct downloads, the response will be the file stream with appropriate headers:
```http
Content-Type: application/pdf
Content-Disposition: attachment; filename="document.pdf"
Content-Length: 1048576
```

## Get Response File
- **Endpoint**: `GET /response-files/{id}`
- **Authentication**: Bearer Token (Admin or Request Owner)
- **Description**: Download or view a response file
- **Access**:
  - Admins can access any response file
  - Users can only access files from responses to their requests

### Response
Same format as Get Request File response.

### Supported File Types
- Documents: PDF, DOC, DOCX
- Images: JPG, PNG
- Maximum file size: 10MB

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
    "message": "You do not have permission to access this file"
}
```

#### 404 Not Found
```json
{
    "status": "error",
    "message": "File not found"
}
```

#### 413 Payload Too Large
```json
{
    "status": "error",
    "message": "File exceeds the maximum allowed size of 10MB"
}
```

#### 422 Unprocessable Entity
```json
{
    "status": "error",
    "message": "Validation failed",
    "errors": {
        "file": ["The file must be a file of type: pdf, doc, docx, jpg, png"],
        "request_id": ["The request id field is required"]
    }
}
```