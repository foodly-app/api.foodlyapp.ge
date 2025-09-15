<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API გამოყენების მაგალითები - Foodly</title>
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
            border-bottom: 3px solid #f39c12;
            padding-bottom: 10px;
        }
        h2 {
            color: #34495e;
            margin-top: 30px;
            border-left: 4px solid #f39c12;
            padding-left: 15px;
        }
        h3 {
            color: #2c3e50;
            margin-top: 25px;
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
        .code-block {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            line-height: 1.4;
        }
        .example-section {
            background: #fef9e7;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            border-left: 4px solid #f39c12;
        }
        .language-tab {
            display: inline-block;
            background: #34495e;
            color: white;
            padding: 8px 15px;
            margin: 5px 5px 0 0;
            border-radius: 5px 5px 0 0;
            cursor: pointer;
            font-size: 0.9em;
        }
        .language-tab.active {
            background: #f39c12;
        }
        .response-example {
            background: #e8f5e8;
            border: 1px solid #27ae60;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .endpoint-box {
            background: #3498db;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }
        .tip-box {
            background: #e8f4f8;
            border-left: 4px solid #3498db;
            padding: 15px;
            margin: 15px 0;
        }
        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .nav-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin: 20px 0;
        }
        .nav-link {
            background: #ecf0f1;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            color: #2c3e50;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            border-color: #f39c12;
            text-decoration: none;
            color: #f39c12;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ url('/docs') }}" class="back-link">← დოკუმენტაციის მთავარ გვერდზე დაბრუნება</a>
        
        <h1>📝 API გამოყენების მაგალითები</h1>
        
        <p>აქ მოცემულია პრაქტიკული მაგალითები Foodly API-ის გამოყენებისთვის სხვადასხვა პროგრამირების ენებში.</p>

        <div class="nav-links">
            <a href="#authentication" class="nav-link">🔐 Authentication</a>
            <a href="#javascript" class="nav-link">📜 JavaScript</a>
            <a href="#php" class="nav-link">🐘 PHP</a>
            <a href="#python" class="nav-link">🐍 Python</a>
            <a href="#curl" class="nav-link">🔧 cURL</a>
            <a href="#postman" class="nav-link">📮 Postman</a>
        </div>

        <h2 id="authentication">🔐 Authentication მაგალითები</h2>
        
        <div class="example-section">
            <h3>1. იუზერის რეგისტრაცია</h3>
            <div class="endpoint-box">POST {{ config('app.url') }}/api/auth/register</div>
            
            <div class="language-tab active">JavaScript</div>
            <div class="code-block">
const registerUser = async () => {
    try {
        const response = await fetch('{{ config('app.url') }}/api/auth/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name: 'ნიკა ლომიძე',
                email: 'nika@example.com',
                password: 'password123',
                password_confirmation: 'password123'
            })
        });
        
        const data = await response.json();
        console.log('Registration successful:', data);
        
        // შენახე token LocalStorage-ში
        localStorage.setItem('auth_token', data.data.token);
        
    } catch (error) {
        console.error('Registration failed:', error);
    }
};

registerUser();
            </div>

            <div class="language-tab">PHP</div>
            <div class="code-block">
&lt;?php
$url = '{{ config('app.url') }}/api/auth/register';
$data = [
    'name' => 'ნიკა ლომიძე',
    'email' => 'nika@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123'
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $result = json_decode($response, true);
    $token = $result['data']['token'];
    echo "Registration successful. Token: " . $token;
} else {
    echo "Registration failed: " . $response;
}
?&gt;
            </div>

            <div class="response-example">
                <h4>📥 Response მაგალითი:</h4>
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

        <div class="example-section">
            <h3>2. იუზერის ავტორიზაცია</h3>
            <div class="endpoint-box">POST {{ config('app.url') }}/api/auth/login</div>
            
            <div class="language-tab active">JavaScript</div>
            <div class="code-block">
const loginUser = async (email, password) => {
    try {
        const response = await fetch('{{ config('app.url') }}/api/auth/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                email: email,
                password: password
            })
        });
        
        if (response.ok) {
            const data = await response.json();
            console.log('Login successful:', data);
            
            // შენახე token
            localStorage.setItem('auth_token', data.data.token);
            
            return data.data.token;
        } else {
            throw new Error('Login failed');
        }
        
    } catch (error) {
        console.error('Login error:', error);
        return null;
    }
};

// გამოყენება
loginUser('nika@example.com', 'password123');
            </div>
        </div>

        <h2 id="javascript">📜 JavaScript მაგალითები</h2>
        
        <div class="example-section">
            <h3>3. რესტორნების სიის მიღება</h3>
            <div class="endpoint-box">GET {{ config('app.url') }}/api/website/restaurants</div>
            
            <div class="code-block">
const getRestaurants = async () => {
    try {
        const response = await fetch('{{ config('app.url') }}/api/website/restaurants', {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });
        
        const restaurants = await response.json();
        console.log('Restaurants:', restaurants);
        
        // DOM-ში ჩაშენება
        const restaurantList = document.getElementById('restaurant-list');
        restaurants.data.forEach(restaurant => {
            const div = document.createElement('div');
            div.innerHTML = `
                &lt;h3&gt;${restaurant.name}&lt;/h3&gt;
                &lt;p&gt;${restaurant.description}&lt;/p&gt;
                &lt;p&gt;მისამართი: ${restaurant.address}&lt;/p&gt;
            `;
            restaurantList.appendChild(div);
        });
        
    } catch (error) {
        console.error('Error fetching restaurants:', error);
    }
};

getRestaurants();
            </div>
        </div>

        <div class="example-section">
            <h3>4. Authenticated მოთხოვნა (Android API)</h3>
            <div class="endpoint-box">GET {{ config('app.url') }}/api/android/restaurants</div>
            
            <div class="code-block">
const getRestaurantsAuth = async () => {
    const token = localStorage.getItem('auth_token');
    
    if (!token) {
        console.error('No auth token found');
        return;
    }
    
    try {
        const response = await fetch('{{ config('app.url') }}/api/android/restaurants', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.status === 401) {
            console.error('Token expired or invalid');
            // Redirect to login
            window.location.href = '/login';
            return;
        }
        
        const restaurants = await response.json();
        console.log('Authenticated restaurants:', restaurants);
        
    } catch (error) {
        console.error('Error:', error);
    }
};

getRestaurantsAuth();
            </div>
        </div>

        <h2 id="php">🐘 PHP მაგალითები</h2>
        
        <div class="example-section">
            <h3>5. რესტორნის მენიუს მიღება</h3>
            <div class="endpoint-box">GET {{ config('app.url') }}/api/website/restaurants/{slug}/menu</div>
            
            <div class="code-block">
&lt;?php
class FoodlyAPI {
    private $baseUrl = '{{ config('app.url') }}/api';
    private $token = null;
    
    public function setToken($token) {
        $this->token = $token;
    }
    
    public function getRestaurantMenu($slug) {
        $url = $this->baseUrl . "/website/restaurants/{$slug}/menu";
        
        $headers = [
            'Accept: application/json'
        ];
        
        if ($this->token) {
            $headers[] = "Authorization: Bearer {$this->token}";
        }
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200) {
            return json_decode($response, true);
        } else {
            throw new Exception("API request failed: {$response}");
        }
    }
}

// გამოყენება
$api = new FoodlyAPI();
try {
    $menu = $api->getRestaurantMenu('restaurant-slug');
    
    echo "&lt;h2&gt;მენიუ&lt;/h2&gt;";
    foreach ($menu['data']['categories'] as $category) {
        echo "&lt;h3&gt;{$category['name']}&lt;/h3&gt;";
        foreach ($category['items'] as $item) {
            echo "&lt;p&gt;{$item['name']} - {$item['price']} ლარი&lt;/p&gt;";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?&gt;
            </div>
        </div>

        <h2 id="python">🐍 Python მაგალითები</h2>
        
        <div class="example-section">
            <h3>6. Python Requests ბიბლიოთეკით</h3>
            
            <div class="code-block">
import requests
import json

class FoodlyAPI:
    def __init__(self, base_url='{{ config('app.url') }}/api'):
        self.base_url = base_url
        self.token = None
        self.session = requests.Session()
        
    def set_token(self, token):
        self.token = token
        self.session.headers.update({
            'Authorization': f'Bearer {token}',
            'Accept': 'application/json'
        })
    
    def login(self, email, password):
        """იუზერის ავტორიზაცია"""
        url = f'{self.base_url}/auth/login'
        data = {
            'email': email,
            'password': password
        }
        
        response = self.session.post(url, json=data)
        
        if response.status_code == 200:
            result = response.json()
            token = result['data']['token']
            self.set_token(token)
            return token
        else:
            raise Exception(f'Login failed: {response.text}')
    
    def get_restaurants(self):
        """რესტორნების სიის მიღება"""
        url = f'{self.base_url}/website/restaurants'
        response = self.session.get(url)
        
        if response.status_code == 200:
            return response.json()
        else:
            raise Exception(f'Request failed: {response.text}')
    
    def get_spots(self):
        """სპოტების სიის მიღება (Android API)"""
        url = f'{self.base_url}/android/spots'
        response = self.session.get(url)
        
        if response.status_code == 200:
            return response.json()
        elif response.status_code == 401:
            raise Exception('Authentication required')
        else:
            raise Exception(f'Request failed: {response.text}')

# გამოყენება
api = FoodlyAPI()

try:
    # ავტორიზაცია
    token = api.login('user@example.com', 'password')
    print(f'Login successful, token: {token[:20]}...')
    
    # რესტორნების მიღება
    restaurants = api.get_restaurants()
    print(f'Found {len(restaurants["data"])} restaurants')
    
    # სპოტების მიღება (auth საჭიროა)
    spots = api.get_spots()
    print(f'Found {len(spots["data"])} spots')
    
except Exception as e:
    print(f'Error: {e}')
            </div>
        </div>

        <h2 id="curl">🔧 cURL მაგალითები</h2>
        
        <div class="example-section">
            <h3>7. cURL Commands</h3>
            
            <div class="tip-box">
                <strong>💡 Tip:</strong> Terminal-ში ან Command Prompt-ში გაუშვით ეს ბრძანებები
            </div>
            
            <h4>რეგისტრაცია:</h4>
            <div class="code-block">
curl -X POST {{ config('app.url') }}/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "ნიკა ლომიძე",
    "email": "nika@example.com", 
    "password": "password123",
    "password_confirmation": "password123"
  }'
            </div>
            
            <h4>ავტორიზაცია:</h4>
            <div class="code-block">
curl -X POST {{ config('app.url') }}/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "nika@example.com",
    "password": "password123"
  }'
            </div>
            
            <h4>რესტორნების სია:</h4>
            <div class="code-block">
curl -X GET {{ config('app.url') }}/api/website/restaurants \
  -H "Accept: application/json"
            </div>
            
            <h4>Authenticated მოთხოვნა:</h4>
            <div class="code-block">
curl -X GET {{ config('app.url') }}/api/android/restaurants \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
            </div>
        </div>

        <h2 id="postman">📮 Postman Configuration</h2>
        
        <div class="example-section">
            <h3>8. Postman-ის კონფიგურაცია</h3>
            
            <div class="warning-box">
                <strong>⚠️ შენიშვნა:</strong> Postman Collection იხილეთ docs/Collection/ ფოლდერში
            </div>
            
            <h4>Environment Variables:</h4>
            <div class="code-block">
{
  "base_url": "{{ config('app.url') }}/api",
  "auth_token": "@{{token}}"
}
            </div>
            
            <h4>Headers Template:</h4>
            <div class="code-block">
Content-Type: application/json
Accept: application/json
Authorization: Bearer @{{auth_token}}
            </div>
            
            <h4>Pre-request Script (Token-ის ავტომატური დაყენება):</h4>
            <div class="code-block">
// Login მოთხოვნის შემდეგ
if (pm.response.code === 200) {
    const responseJson = pm.response.json();
    pm.environment.set("auth_token", responseJson.data.token);
}
            </div>
        </div>

        <h2>🔍 API გამოყენების სცენარები</h2>
        
        <div class="example-section">
            <h3>9. ტიპური გამოყენების შემთხვევები</h3>
            
            <h4>Website-ისთვის:</h4>
            <ul>
                <li>რესტორნების კატალოგის ჩვენება</li>
                <li>მენიუს ნავიგაცია</li>
                <li>მაგიდების ძიება</li>
                <li>ფილტრაცია ქალაქის/კულინარიის მიხედვით</li>
            </ul>
            
            <h4>Mobile App-ისთვის (Android/iOS):</h4>
            <ul>
                <li>იუზერის ავტორიზაცია</li>
                <li>ლოკაციაზე დამყარებული ძიება</li>
                <li>ფავორიტების მართვა</li>
                <li>ნოტიფიკაციები</li>
            </ul>
            
            <h4>Kiosk სისტემისთვის:</h4>
            <ul>
                <li>რესტორნის შიდა სისტემა</li>
                <li>POS ინტეგრაცია</li>
                <li>ოფლაინ მუშაობა</li>
                <li>რეპორტინგი</li>
            </ul>
        </div>

        <div class="tip-box">
            <h3>🚀 დამატებითი რესურსები</h3>
            <ul>
                <li><a href="{{ url('/docs') }}">მთავარი დოკუმენტაცია</a></li>
                <li><a href="{{ url('/docs/auth') }}">Authentication API</a></li>
                <li><a href="{{ url('/docs/website') }}">Website API</a></li>
                <li>Postman Collection: <code>docs/Collection/</code></li>
                <li>Error Codes რეფერენსი</li>
            </ul>
        </div>
    </div>
</body>
</html>