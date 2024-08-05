<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class FullCalenderController extends Controller
{
    /**
     * Display events based on the given date range.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Event::whereDate('start', '>=', $request->start)
                        ->whereDate('end', '<=', $request->end)
                        ->get(['id', 'title', 'start', 'end', 'color', 'description', 'room']); // Include room in the query

            return response()->json($data);
        }

        return view('fullcalender');
    }

    /**
     * Handle CRUD operations for events.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax(Request $request)
    {
        switch ($request->type) {
            case 'add':
                $event = Event::create([
                    'title' => $request->title,
                    'start' => $request->start,
                    'end' => $request->end,
                    'color' => $request->color,
                    'description' => $request->description, // Add description
                    'room' => $request->room, // Add room
                    'dresscode' => $request->dresscode
                ]);

                return response()->json($event);
                break;

            case 'update':
                $event = Event::find($request->id);
                if ($event) {
                    $event->update([
                        'title' => $request->title,
                        'start' => $request->start,
                        'end' => $request->end,
                        'color' => $request->color,
                        'description' => $request->description, // Update description
                        'room' => $request->room, // Update room
                        'dresscode' => $request->dresscode
                    ]);

                    return response()->json($event);
                } else {
                    return response()->json(['error' => 'Event not found'], 404);
                }
                break;

            case 'delete':
                $event = Event::find($request->id);
                if ($event) {
                    $event->delete();
                    return response()->json(['success' => true]);
                } else {
                    return response()->json(['error' => 'Event not found'], 404);
                }
                break;

            default:
                return response()->json(['error' => 'Invalid request type'], 400);
                break;
        }
    }

    public function agenda()
    {
        $today = date('Y-m-d'); // Mendapatkan tanggal hari ini
        $events = Event::whereDate('start', '>=', $today) // Mengambil acara mulai dari hari ini dan seterusnya
                       ->get(['id', 'title', 'start', 'end', 'color', 'description', 'room','dresscode']); // Include room

        return view('agenda', compact('events'));
    }

    public function searchEvents(Request $request)
    {
        $query = $request->input('query');
        
        $events = Event::where('title', 'like', "%$query%")
                        ->orWhere('description', 'like', "%$query%")
                        ->orWhere('room', 'like', "%$query%") // Include room in search
                        ->get();

        return view('agenda', compact('events'));
    }
}

