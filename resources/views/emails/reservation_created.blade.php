<p>თქვენი ჯავშანი მიღებულია:</p>
<ul>
    <li>ჯავშნის ID: {{ $reservation->id }}</li>
    <li>თარიღი: {{ $reservation->reservation_date }}</li>
    <li>დრო: {{ $reservation->time_from }} - {{ $reservation->time_to }}</li>
    <li>პირდაპირი რეზერვაბლის ინფორმაცია: {{ $reservation->reservable?->name ?? '—' }}</li>
    <li>სტატუსი: {{ is_object($reservation->status) ? $reservation->status->value : $reservation->status }}</li>
</ul>
<p>თუ ეს არანაირად თქვენ არ შექმენით, უბრალოდ დაგვიკავშირდით.</p>