<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class AdminUsersController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function usersIndex()
    {
        $users = User::select('id', 'name', 'email', 'role', 'phone_number')
            ->latest()
            ->get();

        return Inertia::render('Dashboard', [
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone_number' => 'required|string|max:30',
                'photo_url' => 'nullable|url',
                'role' => [
                    'nullable',
                    Rule::in(['cliente', 'barbero', 'admin'])
                ],
            ]);

            $validatedData['role'] = $validatedData['role'] ?? 'cliente';
            $validatedData['password'] = Hash::make($validatedData['password']);

            User::create($validatedData);
            return redirect()->route('dashboard')->with('success', 'User created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error ocurred while processing your request.']);
        }
    }

    public function delete($id){
        try{
            $user = User::find($id);
            if(!$user){
                return redirect()->back()->withErrors([
                    'error' => 'User not found.'
                ]);
            } else{
                $user->delete();
                return redirect()->route('dashboard')->with('success', 'User deleted successfully.');
            }
        }catch(Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'An error ocurred while processing your request.'
            ]);
        }
    }
}
