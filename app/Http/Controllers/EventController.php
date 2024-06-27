<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EventController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        return Event::all();
    }

    public function store(Request $request)
    {
        Log::info('Request data: ', $request->all());

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'organizers' => 'required|string',
            'photos' => 'nullable|string',
        ]);

        Log::info('Validated data: ', $validatedData);

        try {
            $event = Event::create([
                'name' => $validatedData['name'],
                'location' => $validatedData['location'],
                'start_time' => $validatedData['start_time'],
                'end_time' => $validatedData['end_time'],
                'organizers' => $validatedData['organizers'],
                'photos' => $validatedData['photos'] ?? null,
                'user_id' => $request->user()->id
            ]);

            Log::info('Event created: ', $event->toArray());

            return response()->json($event, 201);
        } catch (\Exception $e) {
            Log::error('Error creating event: ' . $e->getMessage());

            return response()->json(['message' => 'Failed to create event'], 500);
        }
    }

    public function update(Request $request, Event $event)
    {
        // Log the incoming request data and the event being updated
        Log::info('Update request data: ', $request->all());
        Log::info('Event before update: ', $event->toArray());

        // Log authorization check
        Log::info('Authorizing update for event ID: ' . $event->id);
        //$this->authorize('update', $event);

        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'location' => 'sometimes|string|max:255',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date',
            'organizers' => 'sometimes|string',
            'photos' => 'sometimes|string',
        ]);

        // Log the validated data
        Log::info('Validated data: ', $validatedData);

        // Try to update the event
        try {
            $event->update($validatedData);

            // Log the updated event data
            Log::info('Event after update: ', $event->toArray());

            return response()->json($event);
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error updating event: ' . $e->getMessage());

            return response()->json(['message' => 'Failed to update event'], 500);
        }
    }


    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }
}
