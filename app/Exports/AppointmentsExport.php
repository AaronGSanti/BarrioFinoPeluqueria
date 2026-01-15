<?php

namespace App\Exports;

use App\Models\Cita;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AppointmentsExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Cita::with([
            'cliente:id,name',
            'barbero:id,name',
            'servicio:id,nombre'
        ])
            ->get()
            ->map(function ($cita) {
                return [
                    'cliente_id' => $cita->cliente?->name ?? '',
                    'barbero_id' => $cita->barbero?->name ?? 'Sin asignar',
                    'servicio_id' => $cita->servicio?->nombre ?? '',
                    'fecha_hora' => optional($cita->fecha_hora)->format('Y-m-d'),
                    'hora_inicio' => $cita->hora_inicio ? substr($cita->hora_inicio, 0, 5) : '',
                    'estado' => $cita->estado,
                    'precio_total' => $cita->precio_total,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Cliente',
            'Barbero',
            'Servicio',
            'Fecha',
            'Hora',
            'Estado',
            'Precio'
        ];
    }
}
