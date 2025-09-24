<h3>Reservation Reminder #{{ $reservation->id }}</h3>
<p>Dear {{ $recipientType === 'client' ? ($reservation->name ?? 'Customer') : 'Manager' }},</p>
<p>This is a reminder for the upcoming reservation:</p>
<ul>
    <li>Date: {{ $reservation->reservation_date }}</li>
    <li>Time: {{ $reservation->time_from }} - {{ $reservation->time_to }}</li>
    <li>Guests: {{ $reservation->guests_count }}</li>
</ul>
<p>Thank you,
<br/>Foodly Team</p>