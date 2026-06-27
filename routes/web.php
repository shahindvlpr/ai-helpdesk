<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Ticket\Index as TicketIndex;
use App\Livewire\Ticket\Create as TicketCreate;
use App\Livewire\Ticket\Show as TicketShow;
use App\Livewire\Ticket\Edit as TicketEdit;
use App\Livewire\Knowledge\Index as KnowledgeIndex;
use App\Livewire\Knowledge\Create as KnowledgeCreate;
use App\Livewire\Knowledge\Show as KnowledgeShow;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// ============================================
// Home Route
// ============================================
Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

// ============================================
// Authentication Routes (Guest Middleware)
// ============================================
Route::middleware('guest')->group(function () {

    // ===== Login Routes =====
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    });

    // ===== Register Routes =====
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/register', function (Request $request) {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'accepted',
        ]);

        $fullName = $validated['first_name'] . ' ' . $validated['last_name'];
        
        $user = \App\Models\User::create([
            'name' => $fullName,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => bcrypt($validated['password']),
            'role' => 'customer',
            'is_active' => true,
        ]);

        // Auto login after registration
        auth()->login($user);

        return redirect()->route('dashboard');
    });
});

// ============================================
// Logout Route
// ============================================
Route::post('/logout', function (Request $request) {
    auth()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// ============================================
// Protected Routes (Auth Middleware)
// ============================================
Route::middleware(['auth'])->group(function () {

    // ===== Dashboard =====
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return view('dashboard.admin');
        } elseif (auth()->user()->isAgent()) {
            return view('dashboard.agent');
        }
        return view('dashboard.customer');
    })->name('dashboard');

    // ===== Ticket Routes =====
    Route::get('/tickets', TicketIndex::class)->name('tickets.index');
    Route::get('/tickets/create', TicketCreate::class)->name('tickets.create');
    Route::get('/tickets/{ticket}', TicketShow::class)->name('tickets.show');
    Route::get('/tickets/{ticket}/edit', TicketEdit::class)->name('tickets.edit');

    // ===== Knowledge Base Routes =====
    Route::get('/knowledge', KnowledgeIndex::class)->name('knowledge.index');
    Route::get('/knowledge/create', KnowledgeCreate::class)->name('knowledge.create');
    Route::get('/knowledge/{article}', KnowledgeShow::class)->name('knowledge.show');

    // ===== Profile Route =====
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
});