<h3>Reservation #{{ $reservation->id }} - {{ ucfirst($status) }}</h3>
<p>Dear {{ $recipientType === 'client' ? ($reservation->name ?? 'Customer') : 'Manager' }},</p>
<p>The reservation details:</p>
<ul>
    <li>ID: {{ $reservation->id }}</li>
    <li>Date: {{ $reservation->reservation_date }}</li>
    <li>Time: {{ $reservation->time_from }} - {{ $reservation->time_to }}</li>
    <li>Guests: {{ $reservation->guests_count }}</li>
    <li>Status: {{ $status }}</li>
</ul>
<p>Thank you,
<br/>Foodly Team</p>