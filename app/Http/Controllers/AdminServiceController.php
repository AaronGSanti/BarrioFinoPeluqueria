<?php

namespace App\Http\Controllers;

use App\Exports\ServicesExport;
use App\Models\Servicio;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;


class AdminServiceController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'precio' => 'required|integer|min:0',
                'descripcion' => 'nullable|string',
                'duracion' => 'nullable|integer|min:0'
            ]);

            Servicio::create($validatedData);
            return redirect()->route('dashboard', ['tab' => 'servicios'])->with('success', 'Service created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'An error ocurred while processing your request'
            ]);
        }
    }

    public function delete($id)
    {
        $service = Servicio::find($id);
        if (!$service) {
            return back()->wirhErrors([
                'error' => "Service not found"
            ]);
        }

        $service->delete();
        return back()->with('success', 'Service deleted successfully.');
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'precio' => 'required|integer|min:0',
                'descripcion' => 'nullable|string',
                'duracion' => 'nullable|integer|min:0'
            ]);

            $service = Servicio::find($id);

            if (!$service) {
                return back()->withErrors([
                    'error' => "Service not found"
                ]);
            }

            $service->update($validatedData);
            return redirect()->route('dashboard', ['tab' => 'servicios'])->with('success', 'Service updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'An error ocurred while processing your request.'
            ]);
        }
    }

    public function showServices(Request $request)
    {
        $buscador = $request->input('buscador');

        $services = Servicio::when($buscador, function ($query) use ($buscador) {
            $query->where('nombre', 'like', "%{$buscador}%")
                ->orWhere('precio', 'like', "%{$buscador}%")
                ->orWhere('descripcion', 'like', "%{$buscador}%")
                ->orWhere('duracion', 'like', "%{$buscador}%");
        })->get();

        return Inertia::render('Dashboard', [
            'services' => $services,
            'filters' => [
                'buscador' => $buscador
            ],
            'tab' => 'services'
        ]);
    }

    public function export()
    {
        return Excel::download(new ServicesExport, 'services.xlsx');
    }
}
