<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Application;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller {
    public function create() {
        return view('users.create');
    }

    public function show(User $user) {
        $user = Auth::user();
        return view('users.show', compact('user'));
    }

    public function dashboard() {
        $employeesTotal = Employee::count();
        $applicationsTotal = Application::count();
        $equipmentsTotal = Equipment::count();

        return view('dashboards.admin', compact('employeesTotal','applicationsTotal','equipmentsTotal'));
    }

    public function changePassword(Request $request) {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('users.show')->with('success', 'Password changed successfully');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:applicant,admin,accountant,hr,lab', 
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.create')->with('success', 'User created successfully.');
    }

    public function showLoginForm() {
        return view('users.login');
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('dashboards.admin');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
