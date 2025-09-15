<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iOS API - Foodly</title>
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
            border-bottom: 3px solid #007AFF;
            padding-bottom: 10px;
        }
        h2 {
            color: #34495e;
            margin-top: 30px;
            border-left: 4px solid #007AFF;
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
            background-color: #007AFF;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .method-get {
            background-color: #007AFF;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: bold;
        }
        .endpoint-section {
            background: #f0f4ff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            border-left: 4px solid #007AFF;
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
        .ios-icon {
            color: #007AFF;
            font-size: 1.2em;
        }
        .feature-box {
            background: #e8f4fd;
            border: 1px solid #007AFF;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ url('/docs') }}" class="back-link">← დოკუმენტაციის მთავარ გვერდზე დაბრუნება</a>
        
        <h1><span class="ios-icon">🍎</span> iOS API</h1>
        
        <p>iOS API გამოიყენება iOS აპლიკაციისთვის. ის მოიცავს რესტორნების, სპოტების, სივრცეებისა და კულინარიის endpoint-ებს Authentication-ით.</p>

        <div class="warning-box">
            <strong>⚠️ მნიშვნელოვანი:</strong> iOS API-ის უმეტესობა endpoint-ებისთვის საჭიროა Authentication (Bearer Token). 
            პირველ რიგში გააკეთეთ ავტორიზაცია Authentication API-ს გამოყენებით.
        </div>

        <div class="feature-box">
            <h3>🍎 iOS-სპეციფიკური ფუნქციები</h3>
            <ul>
                <li><strong>Swift-ისთვის ოპტიმიზებული:</strong> JSON რესპონსები iOS development-ისთვის</li>
                <li><strong>Core Data მხარდაჭერა:</strong> ეფექტური მონაცემების სინქრონიზაცია</li>
                <li><strong>Push Notifications Ready:</strong> მომავალი ფუნქციონალისთვის მზადყოფნა</li>
                <li><strong>Security:</strong> ყველა endpoint დაცულია Sanctum authentication-ით</li>
            </ul>
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
                        <td><span class="endpoint-url">/api/ios/test</span></td>
                        <td>iOS API ძირითადი ტესტი</td>
                        <td><span class="auth-not-required">არ არის საჭირო</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/ios/spots/test</span></td>
                        <td>Spots კონტროლერის ტესტი</td>
                        <td><span class="auth-not-required">არ არის საჭირო</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/ios/spaces/test</span></td>
                        <td>Spaces კონტროლერის ტესტი</td>
                        <td><span class="auth-not-required">არ არის საჭირო</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2>🏪 რესტორნების API (Protected)</h2>
        <div class="endpoint-section">
            <p>iOS აპლიკაციისთვის რესტორნების მართვა</p>
            
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
                        <td><span class="endpoint-url">/api/ios/restaurants</span></td>
                        <td>iOS-ისთვის რესტორნების სია</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2>📍 Spots API (Protected)</h2>
        <div class="endpoint-section">
            <p>iOS აპლიკაციისთვის სპოტების მართვა</p>
            
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
                        <td><span class="endpoint-url">/api/ios/spots</span></td>
                        <td>ყველა სპოტის სია</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/ios/spots/{slug}</span></td>
                        <td>კონკრეტული სპოტის დეტალები</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/ios/spots/{slug}/restaurants</span></td>
                        <td>სპოტის რესტორნები</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/ios/spots/{slug}/restaurants/top10</span></td>
                        <td>სპოტის ტოპ 10 რესტორანი</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2>🏢 Spaces API (Protected)</h2>
        <div class="endpoint-section">
            <p>iOS აპლიკაციისთვის სივრცეების მართვა</p>
            
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
                        <td><span class="endpoint-url">/api/ios/spaces</span></td>
                        <td>ყველა სივრცის სია</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/ios/spaces/{slug}</span></td>
                        <td>კონკრეტული სივრცის დეტალები</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/ios/spaces/{slug}/restaurants</span></td>
                        <td>სივრცის რესტორნები</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/ios/spaces/{slug}/restaurants/top10</span></td>
                        <td>სივრცის ტოპ 10 რესტორანი</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2>🍽️ Cuisines API (Protected)</h2>
        <div class="endpoint-section">
            <p>iOS აპლიკაციისთვის კულინარიის მართვა</p>
            
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
                        <td><span class="endpoint-url">/api/ios/cuisines</span></td>
                        <td>ყველა კულინარიის სია</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/ios/cuisines/{slug}</span></td>
                        <td>კონკრეტული კულინარიის დეტალები</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/ios/cuisines/{slug}/restaurants</span></td>
                        <td>კულინარიის რესტორნები</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/ios/cuisines/{slug}/restaurants/top10</span></td>
                        <td>კულინარიის ტოპ 10 რესტორანი</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="endpoint-section">
            <h2>🔐 Authentication გამოყენება iOS-ში</h2>
            <p>iOS API-ს გამოსაყენებლად:</p>
            <ol>
                <li>გააკეთეთ ავტორიზაცია <code>/api/auth/login</code>-ით</li>
                <li>მიიღეთ Bearer Token</li>
                <li>შეინახეთ Token iOS Keychain-ში უსაფრთხოებისთვის</li>
                <li>გამოიყენეთ Token ყველა iOS API მოთხოვნაში</li>
            </ol>
            
            <p><strong>Swift URLSession Example:</strong></p>
            <div style="background: #2c3e50; color: #ecf0f1; padding: 15px; border-radius: 5px; margin: 10px 0; font-family: 'Courier New', monospace; font-size: 0.85em;">
var request = URLRequest(url: url)<br>
request.setValue("Bearer \(token)", forHTTPHeaderField: "Authorization")<br>
request.setValue("application/json", forHTTPHeaderField: "Accept")<br>
request.setValue("application/json", forHTTPHeaderField: "Content-Type")<br>
request.httpMethod = "GET"
            </div>
        </div>

        <div class="endpoint-section">
            <h2>📱 iOS Development რეკომენდაციები</h2>
            <ul>
                <li><strong>URLSession:</strong> გამოიყენეთ URLSession API მოთხოვნებისთვის</li>
                <li><strong>Codable:</strong> JSON parsing-ისთვის Codable protocol</li>
                <li><strong>Keychain:</strong> Tokens-ის უსაფრთხო შენახვისთვის</li>
                <li><strong>Network Reachability:</strong> ქსელის მდგომარეობის მონიტორინგი</li>
                <li><strong>Background Tasks:</strong> Data sync background-ში</li>
                <li><strong>Core Data:</strong> Local caching-ისთვის</li>
            </ul>
        </div>

        <div class="endpoint-section">
            <h2>⚠️ Error Handling iOS-ში</h2>
            <p>iOS API შეცდომების მართვა:</p>
            <ul>
                <li><strong>401 Unauthorized:</strong> Token refresh ან re-login</li>
                <li><strong>403 Forbidden:</strong> მომხმარებლის წვდომის შემოწმება</li>
                <li><strong>404 Not Found:</strong> Resource არ არსებობს</li>
                <li><strong>422 Validation Error:</strong> Form validation შეცდომები</li>
                <li><strong>500 Server Error:</strong> სერვერის პრობლემა</li>
                <li><strong>Network Error:</strong> ქსელის კავშირის შეცდომები</li>
            </ul>
            
            <p><strong>Swift Error Handling Example:</strong></p>
            <div style="background: #2c3e50; color: #ecf0f1; padding: 15px; border-radius: 5px; margin: 10px 0; font-family: 'Courier New', monospace; font-size: 0.85em;">
if let httpResponse = response as? HTTPURLResponse {<br>
&nbsp;&nbsp;&nbsp;&nbsp;switch httpResponse.statusCode {<br>
&nbsp;&nbsp;&nbsp;&nbsp;case 401:<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// Handle authentication error<br>
&nbsp;&nbsp;&nbsp;&nbsp;case 404:<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// Handle not found<br>
&nbsp;&nbsp;&nbsp;&nbsp;default:<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// Handle other errors<br>
&nbsp;&nbsp;&nbsp;&nbsp;}<br>
}
            </div>
        </div>
    </div>
</body>
</html>