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
        try {
            return view('users.create');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong while loading the user creation form.');
        }
    }

    public function show(User $user) {
        try {
            $user = Auth::user();
            return view('users.show', compact('user'));
        } catch (Exception $e) {
            \Log::error('Error showing user profile: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load user profile.');
        }
    }

    public function dashboard() {
        try {
            $employeesTotal = Employee::count();
            $applicationsTotal = Application::count();
            $equipmentsTotal = Equipment::count();

            return view('dashboards.admin', compact('employeesTotal', 'applicationsTotal', 'equipmentsTotal'));
        } catch (Exception $e) {
            \Log::error('Error loading dashboard: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load dashboard data.');
        }
    }

    public function changePassword(Request $request) {
        try {
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (Exception $e) {
            \Log::error('Error changing password: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to change password: ' . $e->getMessage());
        }
    }

    public function store(Request $request) {
        try {
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (Exception $e) {
            \Log::error('Error storing user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create user: ' . $e->getMessage())->withInput();
        }
    }

    public function showLoginForm() {
        try {
            return view('users.login');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong while loading the login form.');
        }
    }

    public function login(Request $request) {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if (Auth::attempt($request->only('email', 'password'))) {
                return redirect()->route('dashboards.admin');
            }

            return back()->withErrors(['email' => 'Invalid credentials.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (Exception $e) {
            \Log::error('Error during login: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Login failed. Please try again.');
        }
    }

    public function logout(){
        try {
            Auth::logout();
            return redirect()->route('login')->with('success', 'Logged out successfully.');
        } catch (Exception $e) {
            \Log::error('Error during logout: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to log out. Please try again.');
        }
    }
}
