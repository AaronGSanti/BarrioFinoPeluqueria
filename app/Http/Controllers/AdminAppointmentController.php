<?php

namespace App\Http\Controllers;

use App\Exports\AppointmentsExport;
use App\Models\Cita;
use App\Models\User;
use App\Notifications\CitaRegistrada;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class AdminAppointmentController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'cliente_id' => 'required|exists:users,id',
                'barbero_id' => 'nullable|exists:users,id',
                'servicio_id' => 'required|exists:servicios,id',
                'fecha_hora' => 'required|date_format:Y-m-d',
                'hora_inicio' => 'required|date_format:H:i:s',
                'hora_fin' => 'required|date_format:H:i:s|after:hora_inicio',
                'estado' => 'required|in:pendiente,confirmada,cancelada,completada',
                'precio_total' => 'required|numeric|min:0'
            ]);

            $conflict = Cita::query()
                ->where('barbero_id', $validatedData['barbero_id'])
                ->whereDate('fecha_hora', $validatedData['fecha_hora'])
                ->whereIn('estado', ['pendiente', 'confirmada'])
                ->whereTime('hora_inicio', '<', $validatedData['hora_fin'])
                ->whereTime('hora_fin', '>', $validatedData['hora_inicio'])
                ->exists();

            if ($conflict) {
                return back()->withErrors([
                    'error' => 'Ese horario ya esta ocupado para este barbero.'
                ]);
            }

            $cita = Cita::create($validatedData);
            $cita->load('barbero');

            $cliente = User::find($validatedData['cliente_id']);

            if ($cliente) {
                $cliente->notify(new CitaRegistrada($cita));
            }

            return redirect()->route('dashboard', ['tab' => 'citas'])->with('success', 'Appointment created successfully');
        } catch (Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'An error ocurred while processing your request'
            ]);
        }
    }

    public function delete($id)
    {
        $cita = Cita::find($id);

        if (!$cita) {
            return back()->withErrors([
                'error' => 'Appointnmt not found.'
            ]);
        }

        $cita->delete();
        return back()->with('success', 'Appointment deleted successfully.');
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'cliente_id' => 'required|exists:users,id',
                'barbero_id' => 'nullable|exists:users,id',
                'servicio_id' => 'required|exists:servicios,id',
                'fecha_hora' => 'required|date_format:Y-m-d',
                'hora_inicio' => 'required|date_format:H:i:s',
                'hora_fin' => 'required|date_format:H:i:s|after:hora_inicio',
                'estado' => 'required|in:pendiente,confirmada,cancelada,completada',
                'precio_total' => 'required|numeric|min:0'
            ]);

            if (!empty($validatedData['barbero_id'])) {
                $conflict = Cita::query()
                    ->where('barbero_id', $validatedData['barbero_id'])
                    ->whereDate('fecha_hora', $validatedData['fecha_hora'])
                    ->whereIn('estado', ['pendiente', 'confirmada'])
                    ->whereTime('hora_inicio', '<', $validatedData['hora_fin'])
                    ->whereTime('hora_fin', '>', $validatedData['hora_inicio'])
                    ->where('id', '!=', $id)
                    ->exists();

                if ($conflict) {
                    return back()->withErrors([
                        'error' => 'Ese horario ya esta ocupado para este barbero.'
                    ]);
                }
            }

            $cita = Cita::find($id);
            $cita->update($validatedData);

            return redirect()->route('dashboard', ['tab' => 'citas'])->with('success', 'Appointment updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'An error ocurred while processing your request'
            ]);
        }
    }

    public function showCitas(Request $request)
    {
        $validatedData = $request->validate([
            'buscador' => 'nullable|string|max:100',
            'desde' => 'nullable|date_format:Y-m-d',
            'hasta' => 'nullable|date_format:Y-m-d|after_or_equal:desde',
        ]);

        $buscador = trim((string) ($validatedData['buscador'] ?? ''));
        $desde = $validatedData['desde'] ?? null;
        $hasta = $validatedData['hasta'] ?? null;

        $citas = Cita::query()
            ->with([
                'cliente:id,name',
                'barbero:id,name',
                'servicio:id,nombre'
            ])

            /**Buscador por nombre */
            ->when($buscador, function ($query) use ($buscador) {
                $query->where(function ($q) use ($buscador) {
                    $q->whereHas('cliente', function ($qc) use ($buscador) {
                        $qc->where('name', 'like', "%{$buscador}%");
                    })

                        ->orWhereHas('barbero', function ($qb) use ($buscador) {
                            $qb->where('name', 'like', "%{$buscador}%");
                        })

                        ->orWhereHas('servicio', function ($qs) use ($buscador) {
                            $qs->where('nombre', 'like', "%{$buscador}%");
                        })

                        ->orWhere('estado', 'like', "%{$buscador}%")
                        ->orWhere('fecha_hora', 'like', "%{$buscador}%");
                });
            })

            /** Buscador por rango de fechas */
            ->when($desde, fn($q) => $q->whereDate('fecha_hora', '>=', $desde))
            ->when($hasta, fn($q) => $q->whereDate('fecha_hora', '<=', $hasta))

            ->orderBy('fecha_hora')
            ->orderBy('hora_inicio')
            ->get();

        return Inertia::render('Dashboard', [
            'citas' => $citas,
            'filters' => [
                'buscador' => $buscador,
                'desde' => $desde,
                'hasta' => $hasta
            ],
            'tab' => 'citas'
        ]);
    }

    public function export()
    {
        return Excel::download(new AppointmentsExport, 'appointments.xlsx');
    }
}
