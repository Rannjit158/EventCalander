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

        
        <div class="hidden md:flex space-x-2">
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


        <div class="md:hidden">
            <a href="{{ route('events.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded shadow text-sm">
               + Add
            </a>
        </div>
    </div>


    <div id="calendar"></div>


    <h2 class="text-xl font-semibold mt-8 mb-4 text-blue-700">Event List</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm border border-gray-200 rounded">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="px-4 py-2 w-1/4">Title</th>
                    <th class="px-4 py-2 w-1/4">Date & Time</th>
                    <th class="px-4 py-2 w-1/4">Email</th>
                    <th class="px-4 py-2 w-1/4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                <tr class="border-t hover:bg-gray-50" id="event-row-{{ $event->id }}">
                    <td class="px-4 py-2 font-medium">{{ $event->title }}</td>
                    <td class="px-4 py-2">

                        {{ \Carbon\Carbon::parse($event->event_at)->format('M d, Y h:i A') }}
                    </td>
                    <td class="px-4 py-2">{{ $event->email }}</td>
                    <td class="px-4 py-2 flex space-x-2">
                        <a href="{{ route('events.edit', $event->id) }}"
                           class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded">
                           Edit
                        </a>
                        <button class="deleteEvent bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1 rounded"
                                data-id="{{ $event->id }}">
                            Delete
                        </button>
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
        initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
        height: window.innerWidth < 768 ? 'auto' : 700,

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        events: '{{ route('events.feed') }}',

        eventTimeFormat: {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        },

        eventContent: function(arg) {
            let timeText = arg.timeText;


            if (arg.view.type === 'dayGridMonth' || arg.view.type === 'timeGridWeek') {
                return {
                    html: `
                        <div style="
                            display:inline-block;
                            padding:2px 6px;
                            background:#dbeafe;
                            color:#1e40af;
                            font-size:12px;
                            font-weight:600;
                            border-radius:6px;
                        ">
                            ${timeText}
                        </div>`
                };
            }


            return true;
        }
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
