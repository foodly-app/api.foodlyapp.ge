<p>გამარჯობა {{ $managerName ?? 'მენეჯერო' }},</p>
<p>გახსენით: ახლოვდება ჯავშნის თარიღი:</p>
<ul>
    <li>ჯავშნის ID: {{ $reservation->id }}</li>
    <li>დღე/დრო: {{ $reservation->reservation_date }} {{ $reservation->time_from }}</li>
    <li>სტუმრები: {{ $reservation->guests_count }}</li>
</ul>
