# Users Module – API Usage & Testing Guide

## Endpoint Overview

This module covers all user-related API endpoints for the website platform, including authentication, profile management, and CRUD operations.

### 1. Public Endpoints
- **List Users**: `GET /api/website/users/`
- **Show User**: `GET /api/website/users/{id}`

### 2. Authentication Endpoints
- **Register**: `POST /api/website/auth/register`
- **Login**: `POST /api/website/auth/login`
- **Forgot Password**: `POST /api/website/auth/forgot-password`
- **Reset Password**: `POST /api/website/auth/reset-password`
- **Logout**: `POST /api/website/auth/logout` _(requires Bearer token)_

### 3. Protected User Endpoints (require Bearer token)
- **Get Profile**: `GET /api/website/users/profile`
- **Update Profile**: `POST /api/website/users/profile/update`
- **Update Avatar**: `POST /api/website/users/profile/avatar` _(multipart/form-data)_
- **Delete User**: `DELETE /api/website/users/{id}`

---

## Postman Collection

A ready-to-import Postman collection is available at:
- `docs/Collection/Users_API_Collection.json`

This collection includes all endpoints above, with example requests, environment variables, and test scripts.

---

## Testing Instructions

1. **Set Base URL**
   - Use: `https://api.foodlyapp.ge.test` (or your local domain)
   - In Postman, set `base_url` variable accordingly.

2. **Register & Login**
   - Register a new user via the Register endpoint.
   - Login to receive an `auth_token` (Bearer token).
   - Set `auth_token` in Postman environment for protected requests.

3. **Profile & CRUD**
   - Use the token to access profile, update, avatar, and delete endpoints.
   - For avatar, use form-data and select an image file.

4. **Password Reset**
   - Use Forgot Password to request a reset link (check mail/log for token).
   - Use Reset Password with the token to set a new password.

5. **Error Testing**
   - The collection includes negative test cases (invalid credentials, unauthorized, validation errors).

---

## Notes
- All responses use Resource classes for consistency.
- Auth endpoints are grouped under `/api/website/auth/`.
- User endpoints are grouped under `/api/website/users/`.
- For platform-specific logic, use separate controllers as needed.

---

## Quick Reference
- See also: `docs/QUICK-REFERENCE.md` for a summary of all API endpoints.
- For troubleshooting, check Laravel logs and Postman responses.

---

_Updated: 2025-09-23_