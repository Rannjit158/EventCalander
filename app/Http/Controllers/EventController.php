<?php


namespace App\Http\Controllers;

use App\Models\Event;
use App\Jobs\SendEventRemainderJob;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
        return view('events.index');
    }

    public function feed()
    {
        $events = Event::all()->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->event_at->toISOString(),
                'description' => $event->description,
                'email' => $event->email,
                'extendedProps' => [
                    'description' => $event->description,
                    'email' => $event->email,
                ]
            ];
        });

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_at' => 'required|date|after:now',
            'email' => 'required|email',
            'reminder_minutes_before' => 'required|integer|min:0|max:1440'
        ]);

        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'event_at' => $request->event_at,
            'email' => $request->email,
            'reminder_minutes_before' => $request->reminder_minutes_before,
            'reminder_sent' => false
        ]);

        // Schedule reminder job
        $reminderTime = Carbon::parse($event->event_at)
            ->subMinutes($event->reminder_minutes_before);

        if ($reminderTime->isFuture()) {
            SendEventRemainderJob::dispatch($event)->delay($reminderTime);
        }

        return response()->json([
            'message' => 'Event created successfully',
            'event' => $event
        ]);
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json([
            'message' => 'Event deleted successfully'
        ]);
    }
}
