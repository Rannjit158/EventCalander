@extends('layouts.guest')

@section('title', 'Event Reminder')
@section('header', 'Event Reminder')

@section('content')
    <p class="mb-4">Hello!</p>

    <p class="mb-4">This is a reminder for your upcoming event:</p>

    <div class="bg-gray-50 border-l-4 border-blue-600 p-4 rounded-md shadow-sm mb-4">
        <h3 class="text-lg font-semibold text-gray-900">{{ $event->title }}</h3>

        @if($event->description)
            <p class="mt-2 text-sm"><strong>Description:</strong> {{ $event->description }}</p>
        @endif

        <p class="mt-2 text-sm">
            <strong>Date & Time:</strong> {{ $event->event_at->format('l, F j, Y \a\t g:i A') }}
        </p>

        <p class="mt-2 text-sm">
            <strong>Time remaining:</strong> {{ $event->event_at->diffForHumans() }}
        </p>
    </div>

    <p class="text-gray-700">Don't forget to prepare for your event! 🚀</p>
@endsection
