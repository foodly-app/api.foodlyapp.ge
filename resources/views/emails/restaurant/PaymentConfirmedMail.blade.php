<p>გამარჯობა {{ $managerName ?? 'მენეჯერო' }},</p>
<p>გადახდა მიღებულია ჯავშნისთვის:</p>
<ul>
    <li>ჯავშნის ID: {{ $reservation->id }}</li>
    <li>თანხა: {{ $paymentAmount ?? '—' }}</li>
    <li>სტატუსი: Payment Confirmed</li>
</ul>
