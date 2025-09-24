@extends('emails.layout')

@section('content')
    <p>{{ __('emails.greeting', ['name' => $reservation->name ?? 'კლიენტი']) }}</p>
    <p>თქვენი ჯავშანი დასტურდება:</p>
    <ul>
        <li>ჯავშის ID: {{ $reservation->id }}</li>
        <li>დღე/დრო: {{ $reservation->reservation_date }} {{ $reservation->time_from }} - {{ $reservation->time_to }}</li>
        <li>რესტორანი/სივრცე: {{ $reservation->reservable?->name ?? '—' }}</li>
        <li>სტუმრები: {{ $reservation->guests_count }}</li>
        <li>სტატუსი: Confirmed</li>
    </ul>
    <p>{{ __('emails.thanks', ['app' => config('app.name')]) }}</p>
@endsection
