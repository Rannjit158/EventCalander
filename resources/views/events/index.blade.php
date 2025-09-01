@extends('layouts.guest')

@section('content')
<div class="bg-white p-6 rounded-2xl shadow max-w-full mx-auto">

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

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-blue-700">Event Calendar</h1>

        <div class="flex space-x-2">
            <div class="relative">
                <button id="viewDropdownBtn"
                        class="bg-gray-100 px-4 py-2 rounded shadow hover:bg-gray-200 flex items-center">
                    View
                </button>
                <div id="viewDropdown"
                     class="hidden absolute right-0 mt-2 w-40 bg-white rounded shadow border z-10">
                    <button data-view="dayGridMonth" class="block w-full px-4 py-2 hover:bg-gray-100">Month</button>
                    <button data-view="timeGridWeek" class="block w-full px-4 py-2 hover:bg-gray-100">Week</button>
                    <button data-view="timeGridDay" class="block w-full px-4 py-2 hover:bg-gray-100">Day</button>
                    <button data-view="listWeek" class="block w-full px-4 py-2 hover:bg-gray-100">List</button>
                </div>
            </div>

            <a href="{{ route('events.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
               + Add Event
            </a>
        </div>
    </div>

    <div id="calendar"></div>

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
                @foreach($events as $event)
                <tr class="border-t hover:bg-gray-50" id="event-row-{{ $event->id }}">
                    <td class="px-4 py-2 font-medium">{{ $event->title }}</td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($event->event_at)->format('M d, Y H:i') }}</td>
                    <td class="px-4 py-2">{{ $event->email }}</td>
                    <td class="px-4 py-2">
                        <div class="relative inline-block text-left">
                            <button class="dropdownBtn bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1 rounded" data-id="{{ $event->id }}">
                                Actions ▾
                            </button>
                            <div class="dropdownMenu hidden absolute right-0 mt-2 w-32 bg-white border rounded shadow-lg z-10" id="dropdown-{{ $event->id }}">
                                <a href="{{ route('events.edit', $event->id) }}" class="block px-4 py-2 text-sm hover:bg-gray-100">Edit</a>
                                <button class="deleteEvent block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100" data-id="{{ $event->id }}">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.css' rel='stylesheet'>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js'></script>

<script>
$(document).ready(function() {


    $('#viewDropdownBtn').click(function() {
        $('#viewDropdown').toggleClass('hidden');
    });
    $(document).click(function(e) {
        if (!$(e.target).closest('#viewDropdownBtn, #viewDropdown').length) {
            $('#viewDropdown').addClass('hidden');
        }
    });


    $('.dropdownBtn').click(function(e) {
        e.stopPropagation();
        var id = $(this).data('id');
        $('.dropdownMenu').not('#dropdown-' + id).addClass('hidden');
        $('#dropdown-' + id).toggleClass('hidden');
    });
    $(document).click(function() {
        $('.dropdownMenu').addClass('hidden');
    });

    $(document).on('click', '.deleteEvent', function(e) {
    e.preventDefault();
    var id = $(this).data('id');

    if (!confirm('Are you sure you want to delete this event?')) return;

    $.ajax({
        url: '/events/' + id,
        type: 'DELETE',
        data: { _token: '{{ csrf_token() }}' },
        success: function(response) {

            $('#event-row-' + id).fadeOut(300, function() { $(this).remove(); });


            var eventObj = calendar.getEventById(id);
            if(eventObj) eventObj.remove();

            alert('Event deleted successfully!');
        },
        error: function(err) {
            alert('Failed to delete event!');
        }
    });
});

    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 700,
         timeZone: 'Asia/Kathmandu',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        events: '{{ route('events.feed') }}'
    });
    calendar.render();


    $('#viewDropdown button[data-view]').click(function() {
        var view = $(this).data('view');
        calendar.changeView(view);
        $('#viewDropdown').addClass('hidden');
    });

});
</script>
@endsection
