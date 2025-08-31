<?php


namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{

    public function index()
    {
        $events = Event::orderBy('event_at', 'asc')->get();
        return view('events.index', compact('events'));
    }


    public function create()
    {
        return view('events.create');
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

        Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'event_at' => $request->event_at,
            'email' => $request->email,
            'reminder_minutes_before' => $request->reminder_minutes_before,
            'reminder_sent' => false
        ]);

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }


    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('events.edit', compact('event'));
    }


    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_at' => 'required|date|after:now',
            'email' => 'required|email',
            'reminder_minutes_before' => 'required|integer|min:0|max:1440'
        ]);

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'event_at' => $request->event_at,
            'email' => $request->email,
            'reminder_minutes_before' => $request->reminder_minutes_before,
        ]);

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }


    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }

   
    public function feed()
    {
        $events = Event::all()->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => Carbon::parse($event->event_at)->toISOString(),
                'description' => $event->description,
                'email' => $event->email,
            ];
        });

        return response()->json($events);
    }
}
