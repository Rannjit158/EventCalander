<!-- filepath: resources/views/events/index.blade.php -->
@extends('layouts.guest')

@section('content')
<div class="bg-white p-6 rounded-2xl shadow max-w-full mx-auto">
    <!-- Flash messages -->
    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-blue-700">Event Calendar</h1>

        <div class="flex space-x-2">
            <!-- Dropdown for view switch -->
            <div class="relative">
                <button id="viewDropdownBtn"
                    class="bg-gray-100 px-4 py-2 rounded shadow hover:bg-gray-200 flex items-center">
                    View ▾
                </button>
                <div id="viewDropdown"
                     class="hidden absolute right-0 mt-2 w-40 bg-white rounded shadow border z-10">
                    <button data-view="dayGridMonth" class="block w-full px-4 py-2 hover:bg-gray-100">Month</button>
                    <button data-view="timeGridWeek" class="block w-full px-4 py-2 hover:bg-gray-100">Week</button>
                    <button data-view="timeGridDay" class="block w-full px-4 py-2 hover:bg-gray-100">Day</button>
                    <button data-view="listWeek" class="block w-full px-4 py-2 hover:bg-gray-100">List</button>
                </div>
            </div>

            <!-- Add event button -->
            <a href="{{ route('events.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
               + Add Event
            </a>
        </div>
    </div>

    <!-- Calendar -->
    <div id='calendar'></div>

    <!-- Event List -->

<h2 class="text-xl font-semibold mt-8 mb-4 text-blue-700">Event List</h2>
<div class="overflow-x-auto">
    <table class="w-full text-sm border border-gray-200 rounded">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="px-4 py-2">Title</th>
                <th class="px-4 py-2">Date & Time</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
            <tr class="border-t hover:bg-gray-50">
                <td class="px-4 py-2 font-medium">{{ $event->title }}</td>
                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($event->event_at)->format('M d, Y H:i') }}</td>
                <td class="px-4 py-2">{{ $event->email }}</td>
                <td class="px-4 py-2">
                    <!-- Actions Dropdown -->
                    <div class="relative inline-block text-left">
                        <button onclick="toggleDropdown({{ $event->id }})"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1 rounded">
                            Actions ▾
                        </button>
                        <div id="dropdown-{{ $event->id }}" class="hidden absolute right-0 mt-2 w-32 bg-white border rounded shadow-lg z-10">

                            <a href="{{ route('events.edit', $event->id) }}"
                               class="block px-4 py-2 text-sm hover:bg-gray-100">Edit</a>
                            <form action="{{ route('events.destroy', $event->id) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this event?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-4 py-2 text-gray-500">No events found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    function toggleDropdown(id) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
            if (el.id !== 'dropdown-' + id) el.classList.add('hidden');
        });
        document.getElementById('dropdown-' + id).classList.toggle('hidden');
    }
    // Close dropdown on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('[id^="dropdown-"]') && !e.target.closest('button[onclick^="toggleDropdown"]')) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => el.classList.add('hidden'));
        }
    });
</script>


<!-- FullCalendar CSS/JS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet'>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dropdown toggle
        const dropdownBtn = document.getElementById('viewDropdownBtn');
        const dropdown = document.getElementById('viewDropdown');
        dropdownBtn.onclick = () => dropdown.classList.toggle('hidden');

        // Calendar
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 700,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: '' // views hidden, we use dropdown
            },
            events: '{{ route('events.feed') }}',
        });
        calendar.render();

        // Dropdown view change
        dropdown.querySelectorAll('button[data-view]').forEach(btn => {
            btn.onclick = () => {
                calendar.changeView(btn.dataset.view);
                dropdown.classList.add('hidden');
            };
        });
    });
</script>
@endsection
