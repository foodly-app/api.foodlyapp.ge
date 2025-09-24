<p>გამარჯობა {{ $managerName ?? 'მენეჯერო' }},</p>
<p>ჯავშანი გაუქმდა:</p>
<ul>
    <li>ჯავშნის ID: {{ $reservation->id }}</li>
    <li>პარამეტრები: {{ $reservation->reservation_date }} {{ $reservation->time_from }} - {{ $reservation->time_to }}</li>
    <li>სტატუსი: Cancelled</li>
</ul>
<p>დამატებითი დეტალებისთვის გადახედეთ ადმინისტრაციულ პანელს.</p>
