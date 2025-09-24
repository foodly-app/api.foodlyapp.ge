<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; color:#333 }
        .container { max-width: 680px; margin: 0 auto; padding: 20px }
        .footer { margin-top: 30px; font-size: 12px; color:#888 }
    </style>
    <title>{{ config('app.name') }}</title>
 </head>
 <body>
    <div class="container">
        @include('emails.partials.header')
        <div class="content">
            @yield('content')
        </div>
        @include('emails.partials.footer')
    </div>
 </body>
 </html>
