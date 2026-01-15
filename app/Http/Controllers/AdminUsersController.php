<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Models\Cita;
use App\Models\Servicio;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class AdminUsersController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function dashboardIndex(Request $request)
    {
        $tab = $request->input('tab', 'home');

        $users = User::select('id', 'name', 'email', 'role', 'phone_number')
            ->latest()
            ->get();
        $services = Servicio::select('id', 'nombre', 'precio', 'descripcion', 'duracion')
            ->latest()
            ->get();
        $citas = Cita::select('id', 'cliente_id', 'barbero_id', 'servicio_id', 'fecha_hora', 'precio_total', 'estado', 'hora_inicio')
            ->with([
                'cliente:id,name',
                'barbero:id,name',
                'servicio:id,nombre'
            ])
            ->latest()
            ->get();

        return Inertia::render('Dashboard', [
            'users' => $users,
            'services' => $services,
            'citas' => $citas,

            'contadores' => [
                'users' => $users->count(),
                'services' => $services->count(),
                'citas' => $citas->count()
            ],

            'tab' => $tab
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
            return redirect()->route('dashboard', ['tab' => 'users'])->with('success', 'User created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error ocurred while processing your request.']);
        }
    }

    public function delete($id)
    {
        $user = User::find($id);
        if (!$user) {
            return back()->withErrors([
                'error' => "User not found"
            ]);
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                Rule::unique('users', 'email')->ignore($id),
                'password' => 'nullable|string|min:8',
                'phone_number' => 'required|string|max:30',
                'photo_url' => 'nullable|url',
                'role' => [
                    'nullable',
                    Rule::in(['cliente', 'barbero', 'admin'])
                ]
            ]);

            $user = User::find($id);
            if (!$user) {
                return back()->withErrors([
                    'error' => "User not found"
                ]);
            }

            $validatedData['role'] = $validatedData['role'] ?? 'cliente';

            if (!empty($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            } else {
                unset($validatedData['password']);
            }

            $user->update($validatedData);
            return redirect()->route('dashboard', ['tab' => 'users'])->with('success', 'User updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'An error ocurred while processing your
                request.'
            ]);
        }
    }

    public function showUser(Request $request)
    {
        $buscador = $request->input('buscador');

        $users = User::when($buscador, function ($query) use ($buscador) {
            $query->where('name', 'like', "%{$buscador}%")
                ->orWhere('email', 'like', "%{$buscador}%")
                ->orWhere('role', 'like', "%{$buscador}%")
                ->orWhere('phone_number', 'like', "%{$buscador}%");
        })->get();

        return Inertia::render('Dashboard', [
            'users' => $users,
            'filters' => [
                'buscador' => $buscador
            ],
            'tab' => 'users'
        ]);
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}
