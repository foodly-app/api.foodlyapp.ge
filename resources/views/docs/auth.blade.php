<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication API - Foodly</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #e74c3c;
            padding-bottom: 10px;
        }
        h2 {
            color: #34495e;
            margin-top: 30px;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .method-get {
            background-color: #27ae60;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: bold;
        }
        .method-post {
            background-color: #e74c3c;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: bold;
        }
        .code-block {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            overflow-x: auto;
        }
        .endpoint-section {
            background: #ecf0f1;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            border-left: 4px solid #e74c3c;
        }
        .required {
            color: #e74c3c;
            font-weight: bold;
        }
        .optional {
            color: #95a5a6;
            font-style: italic;
        }
        .example-request {
            background: #e8f5e8;
            border: 1px solid #ccffcc;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .example-response {
            background: #ffe8e8;
            border: 1px solid #ffcccc;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ url('/docs') }}" class="back-link">← დოკუმენტაციის მთავარ გვერდზე დაბრუნება</a>
        
        <h1>🔑 Authentication API</h1>
        
        <p>Authentication API გამოიყენება იუზერის რეგისტრაციისთვის, ავტორიზაციისთვის და ტოკენის მართვისთვის.</p>

        <div class="endpoint-section">
            <h2>📋 Authentication Endpoints</h2>
            
            <table>
                <thead>
                    <tr>
                        <th>Method</th>
                        <th>Endpoint</th>
                        <th>აღწერა</th>
                        <th>Authentication</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="method-post">POST</span></td>
                        <td>/api/auth/register</td>
                        <td>ახალი იუზერის რეგისტრაცია</td>
                        <td>არ არის საჭირო</td>
                    </tr>
                    <tr>
                        <td><span class="method-post">POST</span></td>
                        <td>/api/auth/login</td>
                        <td>იუზერის ავტორიზაცია</td>
                        <td>არ არის საჭირო</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td>/api/user</td>
                        <td>ავტორიზებული იუზერის ინფორმაცია</td>
                        <td>საჭიროა</td>
                    </tr>
                    <tr>
                        <td><span class="method-post">POST</span></td>
                        <td>/api/auth/logout</td>
                        <td>იუზერის გამოსვლა</td>
                        <td>საჭიროა</td>
                    </tr>
                    <tr>
                        <td><span class="method-post">POST</span></td>
                        <td>/api/user/refresh-token</td>
                        <td>ტოკენის განახლება</td>
                        <td>საჭიროა</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2>🔐 1. იუზერის რეგისტრაცია</h2>
        <div class="endpoint-section">
            <p><strong>Endpoint:</strong> <code>POST /api/auth/register</code></p>
            
            <h3>📥 Request Parameters</h3>
            <table>
                <thead>
                    <tr>
                        <th>Parameter</th>
                        <th>Type</th>
                        <th>Required</th>
                        <th>აღწერა</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>name</td>
                        <td>string</td>
                        <td><span class="required">✓</span></td>
                        <td>იუზერის სახელი</td>
                    </tr>
                    <tr>
                        <td>email</td>
                        <td>string</td>
                        <td><span class="required">✓</span></td>
                        <td>ელ. ფოსტა (უნიკალური)</td>
                    </tr>
                    <tr>
                        <td>password</td>
                        <td>string</td>
                        <td><span class="required">✓</span></td>
                        <td>პაროლი (მინ. 8 სიმბოლო)</td>
                    </tr>
                    <tr>
                        <td>password_confirmation</td>
                        <td>string</td>
                        <td><span class="required">✓</span></td>
                        <td>პაროლის დადასტურება</td>
                    </tr>
                </tbody>
            </table>

            <div class="example-request">
                <h4>📤 მაგალითი Request</h4>
                <div class="code-block">
POST /api/auth/register
Content-Type: application/json

{
    "name": "ნიკა ლომიძე",
    "email": "nika@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
                </div>
            </div>

            <div class="example-response">
                <h4>📥 მაგალითი Response</h4>
                <div class="code-block">
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "ნიკა ლომიძე",
            "email": "nika@example.com",
            "created_at": "2025-09-16T10:30:00.000000Z"
        },
        "token": "1|abc123def456ghi789..."
    }
}
                </div>
            </div>
        </div>

        <h2>🔓 2. იუზერის ავტორიზაცია</h2>
        <div class="endpoint-section">
            <p><strong>Endpoint:</strong> <code>POST /api/auth/login</code></p>
            
            <h3>📥 Request Parameters</h3>
            <table>
                <thead>
                    <tr>
                        <th>Parameter</th>
                        <th>Type</th>
                        <th>Required</th>
                        <th>აღწერა</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>email</td>
                        <td>string</td>
                        <td><span class="required">✓</span></td>
                        <td>იუზერის ელ. ფოსტა</td>
                    </tr>
                    <tr>
                        <td>password</td>
                        <td>string</td>
                        <td><span class="required">✓</span></td>
                        <td>იუზერის პაროლი</td>
                    </tr>
                </tbody>
            </table>

            <div class="example-request">
                <h4>📤 მაგალითი Request</h4>
                <div class="code-block">
POST /api/auth/login
Content-Type: application/json

{
    "email": "nika@example.com",
    "password": "password123"
}
                </div>
            </div>

            <div class="example-response">
                <h4>📥 მაგალითი Response</h4>
                <div class="code-block">
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "ნიკა ლომიძე",
            "email": "nika@example.com"
        },
        "token": "2|xyz789abc456def123..."
    }
}
                </div>
            </div>
        </div>

        <h2>👤 3. იუზერის ინფორმაცია</h2>
        <div class="endpoint-section">
            <p><strong>Endpoint:</strong> <code>GET /api/user</code></p>
            <p><strong>Authentication:</strong> Bearer Token</p>

            <div class="example-request">
                <h4>📤 მაგალითი Request</h4>
                <div class="code-block">
GET /api/user
Authorization: Bearer 2|xyz789abc456def123...
Accept: application/json
                </div>
            </div>

            <div class="example-response">
                <h4>📥 მაგალითი Response</h4>
                <div class="code-block">
{
    "id": 1,
    "name": "ნიკა ლომიძე",
    "email": "nika@example.com",
    "email_verified_at": null,
    "created_at": "2025-09-16T10:30:00.000000Z",
    "updated_at": "2025-09-16T10:30:00.000000Z"
}
                </div>
            </div>
        </div>

        <h2>🚪 4. იუზერის გამოსვლა</h2>
        <div class="endpoint-section">
            <p><strong>Endpoint:</strong> <code>POST /api/auth/logout</code></p>
            <p><strong>Authentication:</strong> Bearer Token</p>

            <div class="example-request">
                <h4>📤 მაგალითი Request</h4>
                <div class="code-block">
POST /api/auth/logout
Authorization: Bearer 2|xyz789abc456def123...
Accept: application/json
                </div>
            </div>

            <div class="example-response">
                <h4>📥 მაგალითი Response</h4>
                <div class="code-block">
{
    "success": true,
    "message": "Logged out successfully"
}
                </div>
            </div>
        </div>

        <h2>🔄 5. ტოკენის განახლება</h2>
        <div class="endpoint-section">
            <p><strong>Endpoint:</strong> <code>POST /api/user/refresh-token</code></p>
            <p><strong>Authentication:</strong> Bearer Token</p>

            <div class="example-request">
                <h4>📤 მაგალითი Request</h4>
                <div class="code-block">
POST /api/user/refresh-token
Authorization: Bearer 2|xyz789abc456def123...
Accept: application/json
                </div>
            </div>

            <div class="example-response">
                <h4>📥 მაგალითი Response</h4>
                <div class="code-block">
{
    "success": true,
    "message": "Token refreshed successfully",
    "data": {
        "token": "3|new789token456here123..."
    }
}
                </div>
            </div>
        </div>

        <h2>⚠️ Error Responses</h2>
        <div class="endpoint-section">
            <h3>422 Validation Error</h3>
            <div class="code-block">
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password field is required."]
    }
}
            </div>

            <h3>401 Authentication Error</h3>
            <div class="code-block">
{
    "message": "Unauthenticated."
}
            </div>

            <h3>403 Invalid Credentials</h3>
            <div class="code-block">
{
    "success": false,
    "message": "Invalid credentials"
}
            </div>
        </div>
    </div>
</body>
</html>