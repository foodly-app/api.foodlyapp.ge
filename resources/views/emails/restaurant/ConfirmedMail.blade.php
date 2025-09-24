@extends('emails.layout')

@section('content')
    <p>{{ $managerName ? 'გამარჯობა '.$managerName : 'გამარჯობა მენეჯერო' }}</p>
    <p>ჯავშანი დაადასტურა:</p>
    <ul>
        <li>ჯავშნის ID: {{ $reservation->id }}</li>
        <li>პარამეტრები: {{ $reservation->reservation_date }} {{ $reservation->time_from }} - {{ $reservation->time_to }}</li>
        <li>სტუმრები: {{ $reservation->guests_count }}</li>
        <li>სტატუსი: Confirmed</li>
    </ul>
    <p>{{ __('emails.thanks', ['app' => config('app.name')]) }}</p>
@endsection
