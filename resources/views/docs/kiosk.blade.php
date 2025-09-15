<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiosk API - Foodly</title>
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
            border-bottom: 3px solid #9b59b6;
            padding-bottom: 10px;
        }
        h2 {
            color: #34495e;
            margin-top: 30px;
            border-left: 4px solid #9b59b6;
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
            background-color: #9b59b6;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .method-get {
            background-color: #9b59b6;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: bold;
        }
        .endpoint-section {
            background: #f4f1f7;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            border-left: 4px solid #9b59b6;
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
        .kiosk-icon {
            color: #9b59b6;
            font-size: 1.2em;
        }
        .feature-box {
            background: #f0e6ff;
            border: 1px solid #9b59b6;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .note-box {
            background: #e8f4f8;
            border-left: 4px solid #3498db;
            padding: 15px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ url('/docs') }}" class="back-link">← დოკუმენტაციის მთავარ გვერდზე დაბრუნება</a>
        
        <h1><span class="kiosk-icon">🖥️</span> Kiosk API</h1>
        
        <p>Kiosk API გამოიყენება კიოსკ სისტემებისთვის (ტაბლეტები, ყიოსქები, POS სისტემები). ის მოიცავს რესტორნების, სპოტების, სივრცეებისა და კულინარიის endpoint-ებს Authentication-ით.</p>

        <div class="warning-box">
            <strong>⚠️ მნიშვნელოვანი:</strong> Kiosk API-ის უმეტესობა endpoint-ებისთვის საჭიროა Authentication (Bearer Token). 
            კიოსკ სისტემა უნდა ავტორიზდეს სპეციალური kiosk account-ით.
        </div>

        <div class="feature-box">
            <h3>🖥️ Kiosk-სპეციფიკური ფუნქციები</h3>
            <ul>
                <li><strong>Kiosk Mode:</strong> უნტერფეისი ოპტიმიზებულია სენსორულ ეკრანებისთვის</li>
                <li><strong>POS Integration:</strong> მზადყოფნა POS სისტემებთან ინტეგრაციისთვის</li>
                <li><strong>Offline Support:</strong> ძირითადი ფუნქციონალი იმუშავებს ოფლაინ რეჟიმშიც</li>
                <li><strong>Security:</strong> ყველა endpoint დაცულია Sanctum authentication-ით</li>
                <li><strong>Fast Response:</strong> ოპტიმიზებული სწრაფი რესპონსებისთვის</li>
            </ul>
        </div>

        <div class="note-box">
            <strong>📝 შენიშვნა:</strong> Kiosk API-ში Spots/test endpoint დროებით გამორთულია (კომენტარში). 
            აქტიური test endpoints არის მხოლოდ restaurants და spaces.
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
                        <td><span class="endpoint-url">/api/kiosk/test</span></td>
                        <td>Kiosk API ძირითადი ტესტი</td>
                        <td><span class="auth-not-required">არ არის საჭირო</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/kiosk/spaces/test</span></td>
                        <td>Spaces კონტროლერის ტესტი</td>
                        <td><span class="auth-not-required">არ არის საჭირო</span></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="background-color: #f8d7da; color: #721c24; font-style: italic;">
                            <strong>გამორთული:</strong> /api/kiosk/spots/test - დროებით გამორთულია
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2>🏪 რესტორნების API (Protected)</h2>
        <div class="endpoint-section">
            <p>Kiosk სისტემისთვის რესტორნების მართვა</p>
            
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
                        <td><span class="endpoint-url">/api/kiosk/restaurants</span></td>
                        <td>Kiosk-ისთვის რესტორნების სია</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2>📍 Spots API (Protected)</h2>
        <div class="endpoint-section">
            <p>Kiosk სისტემისთვის სპოტების მართვა</p>
            
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
                        <td><span class="endpoint-url">/api/kiosk/spots</span></td>
                        <td>ყველა სპოტის სია</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/kiosk/spots/{slug}</span></td>
                        <td>კონკრეტული სპოტის დეტალები</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/kiosk/spots/{slug}/restaurants</span></td>
                        <td>სპოტის რესტორნები</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/kiosk/spots/{slug}/restaurants/top10</span></td>
                        <td>სპოტის ტოპ 10 რესტორანი</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2>🏢 Spaces API (Protected)</h2>
        <div class="endpoint-section">
            <p>Kiosk სისტემისთვის სივრცეების მართვა</p>
            
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
                        <td><span class="endpoint-url">/api/kiosk/spaces</span></td>
                        <td>ყველა სივრცის სია</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/kiosk/spaces/{slug}</span></td>
                        <td>კონკრეტული სივრცის დეტალები</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/kiosk/spaces/{slug}/restaurants</span></td>
                        <td>სივრცის რესტორნები</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/kiosk/spaces/{slug}/restaurants/top10</span></td>
                        <td>სივრცის ტოპ 10 რესტორანი</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2>🍽️ Cuisines API (Protected)</h2>
        <div class="endpoint-section">
            <p>Kiosk სისტემისთვის კულინარიის მართვა</p>
            
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
                        <td><span class="endpoint-url">/api/kiosk/cuisines</span></td>
                        <td>ყველა კულინარიის სია</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/kiosk/cuisines/{slug}</span></td>
                        <td>კონკრეტული კულინარიის დეტალები</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/kiosk/cuisines/{slug}/restaurants</span></td>
                        <td>კულინარიის რესტორნები</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/kiosk/cuisines/{slug}/restaurants/top10</span></td>
                        <td>კულინარიის ტოპ 10 რესტორანი</td>
                        <td><span class="auth-required">საჭიროა</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="endpoint-section">
            <h2>🔐 Kiosk Authentication</h2>
            <p>Kiosk სისტემისთვის Authentication:</p>
            <ol>
                <li>შექმენით სპეციალური kiosk account</li>
                <li>გააკეთეთ ავტორიზაცია <code>/api/auth/login</code>-ით</li>
                <li>მიიღეთ Bearer Token</li>
                <li>შეინახეთ Token კიოსკის ლოკალურ მეხსიერებაში</li>
                <li>გამოიყენეთ Token ყველა Kiosk API მოთხოვნაში</li>
            </ol>
            
            <p><strong>Header Example:</strong></p>
            <div style="background: #2c3e50; color: #ecf0f1; padding: 15px; border-radius: 5px; margin: 10px 0; font-family: 'Courier New', monospace;">
Authorization: Bearer 1|abc123def456ghi789...<br>
Accept: application/json<br>
Content-Type: application/json
            </div>
        </div>

        <div class="endpoint-section">
            <h2>🖥️ Kiosk Development რეკომენდაციები</h2>
            <ul>
                <li><strong>Touch Interface:</strong> ოპტიმიზაცია სენსორული ეკრანებისთვის</li>
                <li><strong>Local Storage:</strong> ოფლაინ რეჟიმისთვის მონაცემების შენახვა</li>
                <li><strong>Auto-refresh:</strong> ავტომატური მონაცემების განახლება</li>
                <li><strong>Error Recovery:</strong> ქსელის შეცდომების მართვა</li>
                <li><strong>Performance:</strong> სწრაფი ძაბვა და რესპონსი</li>
                <li><strong>Security:</strong> Token-ების უსაფრთხო შენახვა</li>
                <li><strong>Logging:</strong> სისტემის მუშაობის მონიტორინგი</li>
            </ul>
        </div>

        <div class="endpoint-section">
            <h2>🔧 Kiosk Deployment შენიშვნები</h2>
            <ul>
                <li><strong>Hardware:</strong> ტაბლეტები, POS ტერმინალები, სენსორული მონიტორები</li>
                <li><strong>OS Support:</strong> Windows, Android, Linux</li>
                <li><strong>Browser:</strong> Chrome/Chromium რეკომენდებული</li>
                <li><strong>Internet:</strong> WiFi ან ეთერნეტ კავშირი</li>
                <li><strong>Backup:</strong> ინტერნეტის გაქრობისას ოფლაინ მუშაობა</li>
            </ul>
        </div>

        <div class="endpoint-section">
            <h2>⚠️ Error Handling Kiosk-ში</h2>
            <p>Kiosk API შეცდომების მართვა:</p>
            <ul>
                <li><strong>401 Unauthorized:</strong> Token refresh ან admin შეტყობინება</li>
                <li><strong>403 Forbidden:</strong> Kiosk account-ის წვდომის შემოწმება</li>
                <li><strong>404 Not Found:</strong> Default მონაცემების ჩვენება</li>
                <li><strong>422 Validation Error:</strong> ფორმის ვალიდაციის შეცდომები</li>
                <li><strong>500 Server Error:</strong> ოფლაინ რეჟიმზე გადასვლა</li>
                <li><strong>Network Error:</strong> ავტომატური რეტრაი მეხანიზმი</li>
            </ul>
        </div>
    </div>
</body>
</html>