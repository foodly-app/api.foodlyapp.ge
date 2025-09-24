<p>გამარჯობა {{ $reservation->name ?? 'კლიენტი' }},</p>
<p>გახსენებთ, რომ თქვენი ჯავშანი არის: <strong>{{ $reservation->reservation_date }} {{ $reservation->time_from }}</strong></p>
<ul>
    <li>რესტორანი/სივრცე: {{ $reservation->reservable?->name ?? '—' }}</li>
    <li>სტუმრები: {{ $reservation->guests_count }}</li>
</ul>
<p>უკვე მზად ვართ თქვენთვის - მოგვმართეთ თუ უნდა ცვლილებები.</p>
