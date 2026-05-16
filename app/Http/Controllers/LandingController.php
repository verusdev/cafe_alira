<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Notifications\NewEventFromLanding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        $eventTypes = Event::TYPES;
        return view('landing', compact('eventTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string|max:50',
            'client_email' => 'nullable|email|max:255',
            'event_type' => 'required|string|in:' . implode(',', array_keys(Event::TYPES)),
            'event_date' => 'required|date|after_or_equal:today',
            'event_time' => 'nullable',
            'people_count' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        $event = Event::create([
            'client_name' => $validated['client_name'],
            'client_phone' => $validated['client_phone'],
            'client_email' => $validated['client_email'] ?? null,
            'event_type' => $validated['event_type'],
            'event_date' => $validated['event_date'],
            'event_time' => $validated['event_time'] ?? null,
            'people_count' => $validated['people_count'],
            'status' => 'new',
            'notes' => $validated['notes'] ?? null,
        ]);

        $notifiable = User::whereIn('role', ['order_taker', 'manager'])->get();
        foreach ($notifiable as $user) {
            $user->notify(new NewEventFromLanding($event));
        }

        return redirect()->route('landing')->with('success', 'Ваша заявка принята! Мы свяжемся с вами в ближайшее время.');
    }
}
