\
@extends('layouts.guest')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded-2xl shadow">
    <h2 class="text-xl font-semibold mb-4 text-blue-700">Edit Event</h2>

    <form method="POST" action="{{ route('events.update', $event->id) }}" class="space-y-3">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium">Title</label>
            <input name="title" value="{{ $event->title }}" class="w-full border rounded px-3 py-2" required />
        </div>
        <div>
            <label class="block text-sm font-medium">Description</label>
            <textarea name="description" rows="3" class="w-full border rounded px-3 py-2">{{ $event->description }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium">Event Date & Time</label>
            <input type="datetime-local" name="event_at" value="{{ $event->event_at->format('Y-m-d\TH:i') }}" class="w-full border rounded px-3 py-2" required />
        </div>
        <div>
            <label class="block text-sm font-medium">Recipient Email</label>
            <input type="email" name="email" value="{{ $event->email }}" class="w-full border rounded px-3 py-2" required />
        </div>
        <div>
            <label class="block text-sm font-medium">Reminder (minutes before)</label>
            <input type="number" name="reminder_minutes_before" value="{{ $event->reminder_minutes_before }}" class="w-full border rounded px-3 py-2" />
        </div>
        <div class="flex space-x-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Update</button>
            <a href="{{ route('events.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Cancel</a>
        </div>
    </form>
</div>
@endsection
