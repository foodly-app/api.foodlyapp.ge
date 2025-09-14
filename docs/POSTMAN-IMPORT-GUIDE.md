# 📮 Postman Collection Import Guide

> **Latest Version**: v2 (January 2025)  
> **Status**: Production Ready ✅

## 🚀 Quick Import Instructions

### Step 1: Download Files
Download both files from the `docs/` folder:

1. **Collection File**: [`FOODLY-API-v2-Collection.postman_collection.json`](./FOODLY-API-v2-Collection.postman_collection.json)
2. **Environment File**: [`FOODLY-API-Production.postman_environment.json`](./FOODLY-API-Production.postman_environment.json)

### Step 2: Import to Postman

#### Import Collection
1. Open Postman
2. Click **Import** button (top left)
3. Select **Upload Files** tab
4. Choose `FOODLY-API-v2-Collection.postman_collection.json`
5. Click **Import**

#### Import Environment
1. Click **Import** button again
2. Select **Upload Files** tab  
3. Choose `FOODLY-API-Production.postman_environment.json`
4. Click **Import**

#### Activate Environment
1. Look for environment dropdown (top right)
2. Select **"FOODLY API - Production Environment"**
3. Verify `base_url` shows `https://api.foodlyapp.ge`

---

## 🧪 Testing the Collection

### Step 1: Login and Get Token
1. Navigate to **🔐 Authentication** folder
2. Click **"Login User (Website)"** request
3. Click **Send**
4. ✅ **Token automatically saved** to environment variables

### Step 2: Test Protected Endpoints
1. Navigate to **🌐 Website Platform** folder
2. Click **"Get Website Spots"** request
3. Click **Send**
4. ✅ Should return spots data with authentication

### Step 3: Test Other Platforms
Try the same flow for:
- **🏢 Kiosk Platform**
- **📱 Android Platform**  
- **🍎 iOS Platform**

### Step 4: Test Public Endpoints
1. Navigate to **🧪 Public Test Endpoints** folder
2. These work **without authentication**
3. Perfect for quick API health checks

---

## 🔧 Environment Variables

The production environment includes these pre-configured variables:

| Variable | Value | Description |
|----------|-------|-------------|
| `base_url` | `https://api.foodlyapp.ge` | Production API URL |
| `test_user_email` | `davit@foodlyapp.ge` | Test user email |
| `test_user_password` | `11111111` | Test user password |
| `auth_token` | *(auto-filled)* | JWT token from login |
| `user_id` | *(auto-filled)* | User ID from login |
| `user_email` | *(auto-filled)* | User email from login |
| `client_type` | *(auto-filled)* | Client platform type |

---

## 🌟 Collection Features

### 🔐 Authentication
- **Multi-Platform Login**: Separate login endpoints for each platform
- **Auto-Token Storage**: Login requests automatically save tokens
- **Token Validation**: Test scripts verify successful authentication

### 📱 Platform Coverage
- **Kiosk**: Restaurant kiosk terminal endpoints
- **Android**: Mobile app endpoints with create/update examples
- **iOS**: iOS app endpoints with Russian locale examples
- **Website**: Web app endpoints with detailed testing

### 🌍 Localization Examples
- **Georgian (ka)**: Kiosk platform examples
- **English (en)**: Android and Website examples
- **Russian (ru)**: iOS platform examples
- **Turkish (tr)**: Available in API, ready for testing

### 🧪 Testing Scripts
Each request includes test scripts that:
- ✅ Validate HTTP status codes
- ✅ Check response structure
- ✅ Verify data types
- ✅ Store important values to environment

---

## 📋 Complete Endpoint Coverage

### Authentication Endpoints ✅
- User login for all platforms
- User registration
- User logout

### Spots CRUD Operations ✅
- **GET** - List spots (all platforms)
- **GET** - Single spot (with ID)
- **POST** - Create spot (authenticated)
- **PUT** - Update spot (authenticated)
- **DELETE** - Delete spot (authenticated)

### Restaurant Endpoints ✅
- List restaurants for all platforms
- Platform-specific filtering

### Public Test Endpoints ✅
- No authentication required
- Perfect for API health checks
- Platform-specific test responses

---

## 🔍 Troubleshooting

### Common Issues

#### "Unauthenticated" Error
1. Make sure you've run a login request first
2. Check that the token is stored in environment variables
3. Verify the environment is active (production)

#### Wrong Base URL
1. Check environment dropdown shows "FOODLY API - Production Environment"
2. Verify `base_url` variable shows `https://api.foodlyapp.ge`
3. Re-import environment file if needed

#### Token Expired
1. Simply run any login request again
2. New token will automatically replace the old one

### Testing Tips

#### Quick Health Check
Run **"Test Website"** from Public Test Endpoints - no auth needed

#### Platform Switching
Each platform login sets the appropriate client type automatically

#### Locale Testing
Modify `?locale=XX` parameter in request URLs:
- `?locale=ka` for Georgian
- `?locale=en` for English
- `?locale=ru` for Russian
- `?locale=tr` for Turkish

---

## 🆕 What's New in v2

### Major Improvements ✨
- **Production Ready**: Pre-configured for live API
- **Complete CRUD**: Full create, read, update, delete operations
- **Auto-Token Management**: No manual token copying needed
- **Multi-Platform Support**: All 4 platforms with examples
- **Enhanced Testing**: Automated response validation
- **Better Organization**: Clear folder structure with emojis

### Migration from v1
If you have the old collection:
1. Delete old collection and environment
2. Import new v2 files
3. Activate new production environment
4. Everything else works the same, but better! ✨

---

## 📞 Support

If you encounter issues:
1. Check this guide first
2. Verify environment variables are set correctly
3. Test with public endpoints first (no auth required)
4. Check the main [README.md](./README.md) for API status

---

*🎯 **Collection Version**: v2 | 📅 **Last Updated**: January 16, 2025*  
*🌐 **Production API**: https://api.foodlyapp.ge | 🔐 **Ready for Testing** ✅*