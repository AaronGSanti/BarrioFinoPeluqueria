<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Servicio
 * 
 * @property int $id
 * @property string|null $nombre
 * @property float|null $precio
 * @property string|null $descripcion
 * @property string|null $duracion
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|BarberoServicio[] $barbero_servicios
 * @property Collection|Cita[] $citas
 *
 * @package App\Models
 */
class Servicio extends Model
{
	protected $table = 'servicios';

	protected $casts = [
		'precio' => 'float'
	];

	protected $fillable = [
		'nombre',
		'precio',
		'descripcion',
		'duracion'
	];

	public function barbero_servicios()
	{
		return $this->hasMany(BarberoServicio::class);
	}

	public function citas()
	{
		return $this->hasMany(Cita::class);
	}
}
