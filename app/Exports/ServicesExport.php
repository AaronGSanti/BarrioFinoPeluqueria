<?php

namespace App\Exports;

use App\Models\Servicio;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ServicesExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Servicio::select('nombre', 'precio', 'descripcion', 'duracion')->get();
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Precio',
            'Descripcion',
            'Duracion'
        ];
    }
}
