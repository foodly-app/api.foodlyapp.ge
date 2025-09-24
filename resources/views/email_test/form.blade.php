<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Email test</title>
    <style>body{font-family:system-ui,Segoe UI,Roboto,Helvetica,Arial,sans-serif;padding:20px}</style>
</head>
<body>
    <h1>Send test email</h1>
    <p>Current mail config:</p>
    <pre id="config">{{ json_encode($config, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) }}</pre>

    <button id="sendBtn">Send test email to david.gakhokia@gmail.com</button>
    <div style="margin-top:1rem">
    <label for="reservation_id">Reservation ID (optional):</label>
    <input id="reservation_id" name="reservation_id" type="text" style="width:120px;margin-left:8px" placeholder="123">
    <label style="margin-left:8px"><input id="send_sync" type="checkbox"> Send synchronously</label>
    <button id="sendAllBtn" style="margin-left:8px">Send all statuses (QA)</button>
    </div>
    <noscript>
        <form method="POST" action="/email-test/send">
            @csrf
            <button type="submit">Send test email (no-JS)</button>
        </form>
    </noscript>
    <div id="result" style="margin-top:1rem;color:green"></div>

    <script>
    document.getElementById('sendBtn').addEventListener('click', async function (){
        this.disabled = true;
        document.getElementById('result').textContent = 'Sending...';
        try {
            const res = await fetch('/email-test/send', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || '' }});
            const json = await res.json();
            if (res.ok) {
                document.getElementById('result').textContent = json.message || 'OK';
            } else {
                document.getElementById('result').textContent = json.error || 'Error';
                document.getElementById('result').style.color = 'red';
            }
        } catch (e) {
            document.getElementById('result').textContent = e.message;
            document.getElementById('result').style.color = 'red';
        } finally {
            this.disabled = false;
        }
    });

    document.getElementById('sendAllBtn').addEventListener('click', async function (){
        this.disabled = true;
        document.getElementById('result').textContent = 'Queuing all status test emails...';
        try {
            const reservationId = document.getElementById('reservation_id').value || '';
            const sync = document.getElementById('send_sync').checked ? '1' : '0';
            const body = new URLSearchParams();
            if (reservationId) body.append('reservation_id', reservationId);
            if (sync) body.append('sync', sync);

            const res = await fetch('/email-test/send-all-statuses', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || '',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: body.toString()
            });

            const json = await res.json();
            if (res.ok) {
                document.getElementById('result').textContent = json.message || 'OK';
            } else {
                document.getElementById('result').textContent = json.error || 'Error';
                document.getElementById('result').style.color = 'red';
            }
        } catch (e) {
            document.getElementById('result').textContent = e.message;
            document.getElementById('result').style.color = 'red';
        } finally {
            this.disabled = false;
        }
    });
    </script>
</body>
</html>