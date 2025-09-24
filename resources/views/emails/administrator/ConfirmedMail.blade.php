@extends('emails.layout')

@section('content')
    <p>ადმინისტრატორული შეტყობინება:</p>
    <p>ჯავშანი დაადასტურდა სისტემაში.</p>
    <ul>
        <li>ჯავშნის ID: {{ $reservation->id }}</li>
        <li>რეზერვაბლი: {{ $reservation->reservable?->name ?? '—' }}</li>
        <li>სტატუსი: Confirmed</li>
    </ul>
    <p>{{ __('emails.thanks', ['app' => config('app.name')]) }}</p>
@endsection
