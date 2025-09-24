<p>გამარჯობა {{ $reservation->name ?? 'კლიენტი' }},</p>
<p>სამწუხაროდ, თქვენი ჯავშანი გაუქმდა:</p>
<ul>
    <li>ჯავშნის ID: {{ $reservation->id }}</li>
    <li>დღე/დრო: {{ $reservation->reservation_date }} {{ $reservation->time_from }} - {{ $reservation->time_to }}</li>
    <li>რესტორანი/სივრცე: {{ $reservation->reservable?->name ?? '—' }}</li>
    <li>სტატუსი: Cancelled</li>
</ul>
<p>თუ გსურთ დეტალები, გთხოვთ დაუკავშირდით მხარდაჭერას ან რესტორანს.</p>
