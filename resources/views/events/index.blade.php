@extends('layouts.guest')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Calendar Header -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-semibold text-gray-800">Event Calendar</h2>
        <div class="flex items-center gap-3">
            <!-- View Switcher Dropdown -->
            <div class="relative z-50" id="viewDropdown">
                <button id="viewBtn" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg shadow">
                    View ▾
                </button>
                <div id="viewMenu" class="absolute right-0 mt-2 w-40 bg-white border rounded-lg shadow hidden z-50">
                    <button data-view="dayGridMonth" class="block w-full text-left px-4 py-2 hover:bg-gray-100">Month</button>
                    <button data-view="timeGridWeek" class="block w-full text-left px-4 py-2 hover:bg-gray-100">Week</button>
                    <button data-view="timeGridDay" class="block w-full text-left px-4 py-2 hover:bg-gray-100">Day</button>
                    <button data-view="listWeek" class="block w-full text-left px-4 py-2 hover:bg-gray-100">List</button>
                </div>
            </div>

            <!-- Add Event Button -->
            <button id="openFormBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition">
                + Add Event
            </button>
        </div>
    </div>

    <!-- Full Width Calendar -->
    <div class="bg-white rounded-2xl shadow p-6">
        <div id="calendar" class="rounded-lg border border-gray-200 p-2"></div>
    </div>
</div>

<!-- Modal -->
<div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-lg p-6 relative">
        <button id="closeModalBtn" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">&times;</button>
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Add Event</h2>
        <form id="eventForm" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Title</label>
                <input name="title" class="mt-1 w-full border rounded-lg px-3 py-2" required />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="3" class="mt-1 w-full border rounded-lg px-3 py-2"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Event Date & Time</label>
                <input type="datetime-local" name="event_at" class="mt-1 w-full border rounded-lg px-3 py-2" required />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Recipient Email</label>
                <input type="email" name="email" class="mt-1 w-full border rounded-lg px-3 py-2" required />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Reminder (minutes before)</label>
                <input type="number" name="reminder_minutes_before" min="0" max="1440" value="15" class="mt-1 w-full border rounded-lg px-3 py-2" />
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded-lg px-4 py-2">Save Event</button>
        </form>
        <div id="flash" class="hidden mt-4 p-3 rounded-lg text-sm font-medium"></div>
    </div>
</div>

<!-- FullCalendar -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

<!-- Custom Styling -->
<style>
    /* Highlight Today */
    .fc-daygrid-day.fc-today {
        background-color: #dbeafe !important; /* light blue */
        border: 2px solid #2563eb !important; /* blue border */
    }
    .fc-daygrid-day.fc-today .fc-daygrid-day-top {
        font-weight: 600;
        color: #1e40af;
    }
    /* Hover effect for days */
    .fc-daygrid-day:hover {
        background-color: #f0f9ff;
    }
</style>

<script>
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 700,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            events: '{{ route('events.feed') }}',
        });
        calendar.render();

        /
        const viewBtn = document.getElementById('viewBtn');
        const viewMenu = document.getElementById('viewMenu');
        viewBtn.addEventListener('click', () => viewMenu.classList.toggle('hidden'));
        viewMenu.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', () => {
                calendar.changeView(btn.dataset.view);
                viewMenu.classList.add('hidden');
            });
        });

        // Modal open/close
        const modal = document.getElementById('eventModal');
        document.getElementById('openFormBtn').addEventListener('click', () => modal.classList.remove('hidden'));
        document.getElementById('closeModalBtn').addEventListener('click', () => modal.classList.add('hidden'));
        window.addEventListener('click', (e) => { if (e.target === modal) modal.classList.add('hidden'); });

        // Create Event
        document.getElementById('eventForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;
            const data = Object.fromEntries(new FormData(form).entries());

            fetch(`{{ route('events.store') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(async (r) => {
                if(!r.ok) throw await r.json();
                return r.json();
            })
            .then(() => {
                calendar.refetchEvents();
                form.reset();
                modal.classList.add('hidden');
                flash('Event saved & reminder scheduled', 'green');
            })
            .catch(err => {
                let msg = 'Failed to save event';
                if (err.errors) msg = Object.values(err.errors).flat().join(', ');
                flash(msg, 'red');
            });
        });

        function flash(message, color) {
            const flashEl = document.getElementById('flash');
            flashEl.textContent = message;
            flashEl.className = `mt-4 p-3 rounded-lg text-sm font-medium bg-${color}-100 text-${color}-800`;
            flashEl.classList.remove('hidden');
            setTimeout(() => flashEl.classList.add('hidden'), 3000);
        }
    });
</script>
@endsection
