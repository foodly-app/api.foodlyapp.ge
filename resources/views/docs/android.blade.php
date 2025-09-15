<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Android API - Foodly</title>
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
            border-bottom: 3px solid #2ecc71;
            padding-bottom: 10px;
        }
        h2 {
            color: #34495e;
            margin-top: 30px;
            border-left: 4px solid #2ecc71;
            padding-left: 15px;
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
            vertical-align: top;
        }
        th {
            background-color: #2ecc71;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .method-get {
            background-color: #27ae60;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: bold;
        }
        .endpoint-section {
            background: #ecf0f1;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            border-left: 4px solid #2ecc71;
        }
        .endpoint-url {
            font-family: 'Courier New', monospace;
            background: #f1f2f6;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 0.85em;
        }
        .auth-required {
            background-color: #e74c3c;
            color: white;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 0.75em;
            font-weight: bold;
        }
        .auth-not-required {
            background-color: #95a5a6;
            color: white;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 0.75em;
            font-weight: bold;
        }
        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .android-icon {
            color: #2ecc71;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ url('/docs') }}" class="back-link">← დოკუმენტაციის მთავარ გვერდზე დაბრუნება</a>
        
        <h1><span class="android-icon">📱</span> Android API</h1>
        
        <p>Android API გამოიყენება Android აპლიკაციისთვის. ის მოიცავს რესტორნების, სპოტების, სივრცეებისა და კულინარიის endpoint-ებს Authentication-ით.</p>

        <div class="warning-box">
            <strong>⚠️ მნიშვნელოვანი:</strong> Android API-ის უმეტესობა endpoint-ებისთვის საჭიროა Authentication (Bearer Token). 
            პირველ რიგში გააკეთეთ ავტორიზაცია Authentication API-ს გამოყენებით.
        </div>

        <div class="endpoint-section">
            <h2>🔧 Test Endpoints (Public)</h2>
            <p>ტესტირების endpoint-ები რომლებიც არ საჭიროებს Authentication-ს</p>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px;">Method</th>
                        <th style="width: 350px;">Endpoint</th>
                        <th>აღწერა</th>
                        <th style="width: 120px;">Authentication</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/android/test</span></td>
                        <td>Android API ძირითადი ტესტი</td>
                        <td><span class="auth-not-required">არ არის საჭირო</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/android/spots/test</span></td>
                        <td>Spots კონტროლერის ტესტი</td>
                        <td><span class="auth-not-required">არ არის საჭირო</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/android/spaces/test</span></td>
                        <td>Spaces კონტროლერის ტესტი</td>
                        <td><span class="auth-not-required">არ არის საჭირო</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2>🏪 რესტორნების API (Protected)</h2>
        <div class="endpoint-section">
            <p>Android აპლიკაციისთვის რესტორნების მართვა</p>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px;">Method</th>
                        <th style="width: 350px;">Endpoint</th>
                        <th>აღწერა</th>
                        <th style="width: 120px;">Authentication</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/android/restaurants</span></td>
                        <td>Android-ისთვის რესტორნების სია</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2>📍 Spots API (Protected)</h2>
        <div class="endpoint-section">
            <p>Android აპლიკაციისთვის სპოტების მართვა</p>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px;">Method</th>
                        <th style="width: 350px;">Endpoint</th>
                        <th>აღწერა</th>
                        <th style="width: 120px;">Authentication</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/android/spots</span></td>
                        <td>ყველა სპოტის სია</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/android/spots/{slug}</span></td>
                        <td>კონკრეტული სპოტის დეტალები</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/android/spots/{slug}/restaurants</span></td>
                        <td>სპოტის რესტორნები</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/android/spots/{slug}/restaurants/top10</span></td>
                        <td>სპოტის ტოპ 10 რესტორანი</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2>🏢 Spaces API (Protected)</h2>
        <div class="endpoint-section">
            <p>Android აპლიკაციისთვის სივრცეების მართვა</p>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px;">Method</th>
                        <th style="width: 350px;">Endpoint</th>
                        <th>აღწერა</th>
                        <th style="width: 120px;">Authentication</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/android/spaces</span></td>
                        <td>ყველა სივრცის სია</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/android/spaces/{slug}</span></td>
                        <td>კონკრეტული სივრცის დეტალები</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/android/spaces/{slug}/restaurants</span></td>
                        <td>სივრცის რესტორნები</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/android/spaces/{slug}/restaurants/top10</span></td>
                        <td>სივრცის ტოპ 10 რესტორანი</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2>🍽️ Cuisines API (Protected)</h2>
        <div class="endpoint-section">
            <p>Android აპლიკაციისთვის კულინარიის მართვა</p>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px;">Method</th>
                        <th style="width: 350px;">Endpoint</th>
                        <th>აღწერა</th>
                        <th style="width: 120px;">Authentication</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/android/cuisines</span></td>
                        <td>ყველა კულინარიის სია</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/android/cuisines/{slug}</span></td>
                        <td>კონკრეტული კულინარიის დეტალები</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/android/cuisines/{slug}/restaurants</span></td>
                        <td>კულინარიის რესტორნები</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/android/cuisines/{slug}/restaurants/top10</span></td>
                        <td>კულინარიის ტოპ 10 რესტორანი</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="endpoint-section">
            <h2>📱 Android-სპეციფიკური ფუნქციები</h2>
            <p>Android API შექმნილია მობილური აპლიკაციის ოპტიმიზებული ფუნქციონალისთვის:</p>
            <ul>
                <li><strong>Performance:</strong> მობილურისთვის ოპტიმიზებული რესპონსები</li>
                <li><strong>Security:</strong> ყველა endpoint დაცულია authentication-ით</li>
                <li><strong>Localization:</strong> SetLocale middleware-ის მხარდაჭერა</li>
                <li><strong>Testing:</strong> ცალკე test endpoint-ები</li>
            </ul>
        </div>

        <div class="endpoint-section">
            <h2>🔐 Authentication გამოყენება</h2>
            <p>Android API-ს გამოსაყენებლად:</p>
            <ol>
                <li>გააკეთეთ ავტორიზაცია <code>/api/auth/login</code>-ით</li>
                <li>მიიღეთ Bearer Token</li>
                <li>გამოიყენეთ Token ყველა Android API მოთხოვნაში</li>
            </ol>
            
            <p><strong>Header Example:</strong></p>
            <div style="background: #2c3e50; color: #ecf0f1; padding: 15px; border-radius: 5px; margin: 10px 0; font-family: 'Courier New', monospace;">
Authorization: Bearer 1|abc123def456ghi789...
Accept: application/json
Content-Type: application/json
            </div>
        </div>

        <div class="endpoint-section">
            <h2>⚠️ Error Handling</h2>
            <p>Android API შეცდომების ძირითადი ტიპები:</p>
            <ul>
                <li><strong>401 Unauthorized:</strong> Token არასწორია ან ვადაგასულია</li>
                <li><strong>403 Forbidden:</strong> წვდომა აკრძალულია</li>
                <li><strong>404 Not Found:</strong> რესურსი არ მოიძებნა</li>
                <li><strong>422 Validation Error:</strong> მონაცემების ვალიდაციის შეცდომა</li>
                <li><strong>500 Server Error:</strong> სერვერის შიდა შეცდომა</li>
            </ul>
        </div>
    </div>
</body>
</html>