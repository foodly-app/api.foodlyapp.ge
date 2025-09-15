<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodly API დოკუმენტაცია</title>
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
            text-align: center;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        h2 {
            color: #34495e;
            margin-top: 30px;
        }
        .api-section {
            background: #ecf0f1;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            border-left: 4px solid #3498db;
        }
        .nav-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .nav-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-decoration: none;
            transition: transform 0.3s ease;
        }
        .nav-card:hover {
            transform: translateY(-5px);
            text-decoration: none;
            color: white;
        }
        .nav-card h3 {
            margin: 0 0 10px 0;
            font-size: 1.2em;
        }
        .nav-card p {
            margin: 0;
            opacity: 0.9;
        }
        .auth-info {
            background: #ffe8e8;
            border: 1px solid #ffcccc;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .endpoint-count {
            background: #e8f5e8;
            border: 1px solid #ccffcc;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🍽️ Foodly API დოკუმენტაცია</h1>
        
        <div class="api-section">
            <h2>📋 ზოგადი ინფორმაცია</h2>
            <p><strong>Base URL:</strong> <code>{{ config('app.url') }}/api</code></p>
            <p><strong>API ვერსია:</strong> v1.0</p>
            <p><strong>დაბრუნების ფორმატი:</strong> JSON</p>
            <p><strong>Authentication:</strong> Laravel Sanctum (Bearer Token)</p>
        </div>

        <div class="auth-info">
            <h3>🔐 Authentication ინფორმაცია</h3>
            <p>უსაფრთხოება გამოიყენება Sanctum ტოკენებით. ზოგიერთი endpoint-ი საჭიროებს authentication-ს.</p>
            <p><strong>Header:</strong> <code>Authorization: Bearer {your-token}</code></p>
        </div>

        <div class="endpoint-count">
            <h3>📊 Endpoint-ების რაოდენობა</h3>
            <ul>
                <li><strong>Authentication:</strong> 4 endpoints</li>
                <li><strong>Website API:</strong> 60+ endpoints</li>
                <li><strong>Android API:</strong> 15+ endpoints</li>
                <li><strong>iOS API:</strong> 15+ endpoints</li>
                <li><strong>Kiosk API:</strong> 15+ endpoints</li>
            </ul>
        </div>

        <h2>🔗 API კატეგორიები</h2>
        
        <div class="nav-links">
            <a href="{{ url('/docs/auth') }}" class="nav-card">
                <h3>🔑 Authentication</h3>
                <p>იუზერის რეგისტრაცია, ავტორიზაცია და ტოკენის მართვა</p>
            </a>

            <a href="{{ url('/docs/website') }}" class="nav-card">
                <h3>🌐 Website API</h3>
                <p>საიტისთვის გამიზნული API endpoints - რესტორნები, მენიუ, მაგიდები</p>
            </a>

            <a href="{{ url('/docs/android') }}" class="nav-card">
                <h3>📱 Android API</h3>
                <p>Android აპლიკაციისთვის გამიზნული endpoints</p>
            </a>

            <a href="{{ url('/docs/ios') }}" class="nav-card">
                <h3>🍎 iOS API</h3>
                <p>iOS აპლიკაციისთვის გამიზნული endpoints</p>
            </a>

            <a href="{{ url('/docs/kiosk') }}" class="nav-card">
                <h3>🖥️ Kiosk API</h3>
                <p>კიოსკ სისტემისთვის გამიზნული endpoints</p>
            </a>

            <a href="{{ url('/docs/examples') }}" class="nav-card">
                <h3>📝 გამოყენების მაგალითები</h3>
                <p>პრაქტიკული მაგალითები და კოდის ნიმუშები</p>
            </a>
        </div>

        <div class="api-section">
            <h2>🚀 სწრაფი დაწყება</h2>
            <ol>
                <li>გააკეთეთ POST მოთხოვნა <code>/api/auth/register</code> ან <code>/api/auth/login</code> endpoint-ზე</li>
                <li>მიიღეთ authentication token</li>
                <li>გამოიყენეთ token Header-ში: <code>Authorization: Bearer {token}</code></li>
                <li>წვდომა გექნებათ ყველა დაცულ endpoint-ზე</li>
            </ol>
        </div>

        <div class="api-section">
            <h2>📞 კონტაქტი</h2>
            <p>საკითხების შემთხვევაში მიმართეთ განვითარების გუნდს.</p>
        </div>
    </div>
</body>
</html>