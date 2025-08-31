<h2 class="text-xl font-semibold mb-4">Add Event</h2>
<form id="eventForm" class="space-y-3">
    @csrf
    <div>
        <label class="block text-sm font-medium">Title</label>
        <input name="title" class="mt-1 w-full border rounded px-3 py-2" required />
    </div>
    <div>
        <label class="block text-sm font-medium">Description</label>
        <textarea name="description" class="mt-1 w-full border rounded px-3 py-2" rows="3"></textarea>
    </div>
    <div>
        <label class="block text-sm font-medium">Event Date & Time</label>
        <input type="datetime-local" name="event_at" class="mt-1 w-full border rounded px-3 py-2" required />
    </div>
    <div>
        <label class="block text-sm font-medium">Recipient Email</label>
        <input type="email" name="email" class="mt-1 w-full border rounded px-3 py-2" required />
    </div>
    <div>
        <label class="block text-sm font-medium">Reminder (minutes before)</label>
        <input type="number" name="reminder_minutes_before" class="mt-1 w-full border rounded px-3 py-2" min="0" max="1440" value="15" />
    </div>
    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded px-4 py-2">
        Save Event
    </button>
</form>

<div id="flash" class="hidden mt-3 p-2 rounded text-sm"></div>
