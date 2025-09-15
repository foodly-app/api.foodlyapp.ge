<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website API - Foodly</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #27ae60;
            padding-bottom: 10px;
        }
        h2 {
            color: #34495e;
            margin-top: 30px;
            border-left: 4px solid #27ae60;
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
            font-size: 0.9em;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        th {
            background-color: #27ae60;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .method-get {
            background-color: #27ae60;
            color: white;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 0.75em;
            font-weight: bold;
        }
        .endpoint-section {
            background: #ecf0f1;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            border-left: 4px solid #27ae60;
        }
        .param-required {
            color: #e74c3c;
            font-weight: bold;
        }
        .param-optional {
            color: #95a5a6;
            font-style: italic;
        }
        .endpoint-url {
            font-family: 'Courier New', monospace;
            background: #f1f2f6;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 0.85em;
        }
        .description {
            font-size: 0.9em;
            color: #555;
        }
        .nav-sections {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        .nav-section {
            background: #e8f5e8;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #27ae60;
        }
        .nav-section h4 {
            margin: 0 0 10px 0;
            color: #27ae60;
        }
        .nav-section a {
            color: #2c3e50;
            text-decoration: none;
            font-size: 0.9em;
        }
        .nav-section a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ url('/docs') }}" class="back-link">← დოკუმენტაციის მთავარ გვერდზე დაბრუნება</a>
        
        <h1>🌐 Website API</h1>
        
        <p>Website API გამოიყენება ვებსაიტისთვის და შეიცავს ყველა საჯარო endpoint-ს. ძირითადი ფუნქციონალი მოიცავს რესტორნების, მენიუს, მაგიდებისა და სხვა რესურსების მართვას.</p>

        <div class="nav-sections">
            <div class="nav-section">
                <h4>🏪 რესტორნები</h4>
                <a href="#restaurants">რესტორნების API</a>
            </div>
            <div class="nav-section">
                <h4>📍 ადგილები (Spots)</h4>
                <a href="#spots">Spots API</a>
            </div>
            <div class="nav-section">
                <h4>🏢 სივრცეები (Spaces)</h4>
                <a href="#spaces">Spaces API</a>
            </div>
            <div class="nav-section">
                <h4>🍽️ კულინარია</h4>
                <a href="#cuisines">Cuisines API</a>
            </div>
            <div class="nav-section">
                <h4>🍛 კერძები</h4>
                <a href="#dishes">Dishes API</a>
            </div>
            <div class="nav-section">
                <h4>🏙️ ქალაქები</h4>
                <a href="#cities">Cities API</a>
            </div>
            <div class="nav-section">
                <h4>📋 მენიუ კატეგორიები</h4>
                <a href="#menu-categories">Menu Categories API</a>
            </div>
            <div class="nav-section">
                <h4>🍴 მენიუ ელემენტები</h4>
                <a href="#menu-items">Menu Items API</a>
            </div>
            <div class="nav-section">
                <h4>📍 ადგილები (Places)</h4>
                <a href="#places">Places API</a>
            </div>
            <div class="nav-section">
                <h4>🪑 მაგიდები</h4>
                <a href="#tables">Tables API</a>
            </div>
        </div>

        <h2 id="restaurants">🏪 რესტორნების API</h2>
        <div class="endpoint-section">
            <p>რესტორნების, მათი დეტალების, მენიუს და მაგიდების მართვა</p>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px;">Method</th>
                        <th style="width: 350px;">Endpoint</th>
                        <th>აღწერა</th>
                        <th style="width: 100px;">Auth</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/restaurants</span></td>
                        <td>ყველა რესტორნის სია</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/restaurants/{slug}</span></td>
                        <td>კონკრეტული რესტორნის დეტალები slug-ით</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/restaurants/{slug}/details</span></td>
                        <td>რესტორნის სრული დეტალები</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/restaurants/{slug}/places</span></td>
                        <td>რესტორნის ადგილების სია</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/restaurants/{slug}/place/{place}</span></td>
                        <td>კონკრეტული ადგილის დეტალები</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/restaurants/{slug}/place/{place}/tables</span></td>
                        <td>ადგილის მაგიდების სია</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/restaurants/{slug}/place/{place}/table/{table}</span></td>
                        <td>კონკრეტული მაგიდის დეტალები ადგილში</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/restaurants/{slug}/tables</span></td>
                        <td>რესტორნის ყველა მაგიდა</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/restaurants/{slug}/table/{table}</span></td>
                        <td>კონკრეტული მაგიდის დეტალები</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/restaurants/{slug}/menu</span></td>
                        <td>რესტორნის სრული მენიუ</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/restaurants/{slug}/menu/categories</span></td>
                        <td>მენიუ კატეგორიების სია</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/restaurants/{slug}/menu/category/{categorySlug}</span></td>
                        <td>კონკრეტული მენიუ კატეგორია</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/restaurants/{slug}/menu/category/{categorySlug}/items</span></td>
                        <td>კატეგორიის მენიუ ელემენტები</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/restaurants/{slug}/menu/item/{itemSlug}</span></td>
                        <td>კონკრეტული მენიუ ელემენტი</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/restaurants/{slug}/menu/items</span></td>
                        <td>რესტორნის ყველა მენიუ ელემენტი</td>
                        <td>არა</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2 id="spots">📍 Spots API</h2>
        <div class="endpoint-section">
            <p>სპოტების (ადგილების) მართვა და რესტორნების ფილტრაცია</p>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px;">Method</th>
                        <th style="width: 350px;">Endpoint</th>
                        <th>აღწერა</th>
                        <th style="width: 100px;">Auth</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/spots</span></td>
                        <td>ყველა სპოტის სია</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/spots/{slug}</span></td>
                        <td>კონკრეტული სპოტის დეტალები</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/spots/{slug}/restaurants</span></td>
                        <td>სპოტის რესტორნები</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/spots/{slug}/restaurants/top10</span></td>
                        <td>სპოტის ტოპ 10 რესტორანი</td>
                        <td>არა</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2 id="spaces">🏢 Spaces API</h2>
        <div class="endpoint-section">
            <p>სივრცეების მართვა და რესტორნების ფილტრაცია</p>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px;">Method</th>
                        <th style="width: 350px;">Endpoint</th>
                        <th>აღწერა</th>
                        <th style="width: 100px;">Auth</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/spaces</span></td>
                        <td>ყველა სივრცის სია</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/spaces/{slug}</span></td>
                        <td>კონკრეტული სივრცის დეტალები</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/spaces/{slug}/restaurants</span></td>
                        <td>სივრცის რესტორნები</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/spaces/{slug}/restaurants/top10</span></td>
                        <td>სივრცის ტოპ 10 რესტორანი</td>
                        <td>არა</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2 id="cuisines">🍽️ Cuisines API</h2>
        <div class="endpoint-section">
            <p>კულინარიის (საკვების ტიპების) მართვა</p>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px;">Method</th>
                        <th style="width: 350px;">Endpoint</th>
                        <th>აღწერა</th>
                        <th style="width: 100px;">Auth</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/cuisines</span></td>
                        <td>ყველა კულინარიის სია</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/cuisines/{slug}</span></td>
                        <td>კონკრეტული კულინარიის დეტალები</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/cuisines/{slug}/restaurants</span></td>
                        <td>კულინარიის რესტორნები</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/cuisines/{slug}/restaurants/top10</span></td>
                        <td>კულინარიის ტოპ 10 რესტორანი</td>
                        <td>არა</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2 id="dishes">🍛 Dishes API</h2>
        <div class="endpoint-section">
            <p>კერძების მართვა და რესტორნების ძიება კერძების მიხედვით</p>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px;">Method</th>
                        <th style="width: 350px;">Endpoint</th>
                        <th>აღწერა</th>
                        <th style="width: 100px;">Auth</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/dishes</span></td>
                        <td>ყველა კერძის სია</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/dishes/{slug}</span></td>
                        <td>კონკრეტული კერძის დეტალები</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/dishes/{slug}/restaurants</span></td>
                        <td>კერძის რესტორნები</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/dishes/{slug}/restaurants/top10</span></td>
                        <td>კერძის ტოპ 10 რესტორანი</td>
                        <td>არა</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2 id="cities">🏙️ Cities API</h2>
        <div class="endpoint-section">
            <p>ქალაქების მართვა და რესტორნების ფილტრაცია ქალაქების მიხედვით</p>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px;">Method</th>
                        <th style="width: 350px;">Endpoint</th>
                        <th>აღწერა</th>
                        <th style="width: 100px;">Auth</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/cities</span></td>
                        <td>ყველა ქალაქის სია</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/cities/{slug}</span></td>
                        <td>კონკრეტული ქალაქის დეტალები</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/cities/{slug}/restaurants</span></td>
                        <td>ქალაქის რესტორნები</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/cities/{slug}/restaurants/top10</span></td>
                        <td>ქალაქის ტოპ 10 რესტორანი</td>
                        <td>არა</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2 id="menu-categories">📋 Menu Categories API</h2>
        <div class="endpoint-section">
            <p>მენიუ კატეგორიების მართვა და ჰიერარქიული სტრუქტურა</p>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px;">Method</th>
                        <th style="width: 450px;">Endpoint</th>
                        <th>აღწერა</th>
                        <th style="width: 100px;">Auth</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/menu-categories</span></td>
                        <td>ყველა მენიუ კატეგორია</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/menu-categories/hierarchy</span></td>
                        <td>ჰიერარქიული სტრუქტურა (ხე)</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/menu-categories/{id}</span></td>
                        <td>კონკრეტული კატეგორია ID-ით</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/menu-categories/slug/{slug}</span></td>
                        <td>კონკრეტული კატეგორია slug-ით</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/menu-categories/{id}/children</span></td>
                        <td>კატეგორიის შვილები</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/menu-categories/{id}/breadcrumb</span></td>
                        <td>ნავიგაციის გზა</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/menu-categories/restaurant/{slug}</span></td>
                        <td>რესტორნის კატეგორიები</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/menu-categories/restaurant/{slug}/items</span></td>
                        <td>რესტორნის კატეგორიები მენიუ ელემენტებით</td>
                        <td>არა</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2 id="menu-items">🍴 Menu Items API</h2>
        <div class="endpoint-section">
            <p>მენიუ ელემენტების მართვა და სპეციალური ფილტრები</p>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px;">Method</th>
                        <th style="width: 400px;">Endpoint</th>
                        <th>აღწერა</th>
                        <th style="width: 100px;">Auth</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/menu-items</span></td>
                        <td>ყველა მენიუ ელემენტი</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/menu-items/featured</span></td>
                        <td>რჩეული/პოპულარული მენიუ ელემენტები</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/menu-items/discounted</span></td>
                        <td>ფასდაკლებული მენიუ ელემენტები</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/menu-items/vegan</span></td>
                        <td>ვეგანური მენიუ ელემენტები</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/menu-items/gluten-free</span></td>
                        <td>გლუტენის გარეშე მენიუ ელემენტები</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/menu-items/{id}</span></td>
                        <td>კონკრეტული მენიუ ელემენტი ID-ით</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/menu-items/slug/{slug}</span></td>
                        <td>კონკრეტული მენიუ ელემენტი slug-ით</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/menu-items/restaurant/{slug}</span></td>
                        <td>რესტორნის მენიუ ელემენტები</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/menu-items/category/{slug}</span></td>
                        <td>კატეგორიის მენიუ ელემენტები</td>
                        <td>არა</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2 id="places">📍 Places API</h2>
        <div class="endpoint-section">
            <p>ადგილების (Places) მართვა და ძიება</p>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px;">Method</th>
                        <th style="width: 450px;">Endpoint</th>
                        <th>აღწერა</th>
                        <th style="width: 100px;">Auth</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/places</span></td>
                        <td>ყველა ადგილი</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/places/featured</span></td>
                        <td>რჩეული ადგილები</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/places/search</span></td>
                        <td>ადგილების ძიება</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/places/{id}</span></td>
                        <td>კონკრეტული ადგილი ID-ით</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/places/slug/{slug}</span></td>
                        <td>კონკრეტული ადგილი slug-ით</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/places/restaurant/{restaurantId}</span></td>
                        <td>რესტორნის ადგილები ID-ით</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/places/restaurant/slug/{restaurantSlug}</span></td>
                        <td>რესტორნის ადგილები slug-ით</td>
                        <td>არა</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2 id="tables">🪑 Tables API</h2>
        <div class="endpoint-section">
            <p>მაგიდების მართვა, ძიება და ხელმისაწვდომობის შემოწმება</p>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px;">Method</th>
                        <th style="width: 450px;">Endpoint</th>
                        <th>აღწერა</th>
                        <th style="width: 100px;">Auth</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/tables</span></td>
                        <td>ყველა მაგიდა</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/tables/available</span></td>
                        <td>ხელმისაწვდომი მაგიდები</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/tables/search</span></td>
                        <td>მაგიდების ძიება</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/tables/{id}</span></td>
                        <td>კონკრეტული მაგიდა ID-ით</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/tables/slug/{slug}</span></td>
                        <td>კონკრეტული მაგიდა slug-ით</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/tables/restaurant/{restaurantId}</span></td>
                        <td>რესტორნის მაგიდები ID-ით</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/tables/restaurant/slug/{restaurantSlug}</span></td>
                        <td>რესტორნის მაგიდები slug-ით</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/tables/place/{placeId}</span></td>
                        <td>ადგილის მაგიდები</td>
                        <td>არა</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="endpoint-section">
            <h2>🔧 Test Endpoints</h2>
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px;">Method</th>
                        <th style="width: 300px;">Endpoint</th>
                        <th>აღწერა</th>
                        <th style="width: 100px;">Auth</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/test</span></td>
                        <td>ძირითადი ტესტი</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/spots/test</span></td>
                        <td>Spots ტესტი</td>
                        <td>არა</td>
                    </tr>
                    <tr>
                        <td><span class="method-get">GET</span></td>
                        <td><span class="endpoint-url">/api/website/spaces/test</span></td>
                        <td>Spaces ტესტი</td>
                        <td>არა</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>