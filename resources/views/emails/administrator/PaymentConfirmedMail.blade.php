<p>ადმინისტრატორული შეტყობინება:</p>
<p>გადახდა მიღებულია:</p>
<ul>
    <li>ჯავშნის ID: {{ $reservation->id }}</li>
    <li>თანხა: {{ $paymentAmount ?? '—' }}</li>
    <li>სტატუსი: Payment Confirmed</li>
</ul>
