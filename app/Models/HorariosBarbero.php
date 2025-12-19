<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HorariosBarbero
 * 
 * @property int $id
 * @property int $barbero_id
 * @property int|null $dia_semana
 * @property Carbon|null $hora_inicio
 * @property Carbon|null $hora_fin
 * @property string|null $estado
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class HorariosBarbero extends Model
{
	protected $table = 'horarios_barbero';

	protected $casts = [
		'barbero_id' => 'int',
		'dia_semana' => 'int',
		'hora_inicio' => 'datetime',
		'hora_fin' => 'datetime'
	];

	protected $fillable = [
		'barbero_id',
		'dia_semana',
		'hora_inicio',
		'hora_fin',
		'estado'
	];

	/**Esto me permite ingresar en la respuesta JSON ese campo para que salga como dia_nombre el JSON que le paso a continuacion. */
	protected $appends = ['dia_nombre'];

	//JSON formado para los dias de la semana.
	public function getDiaNombreAttribute()
	{
		return [
			1 => 'Lunes',
			2 => 'Martes',
			3 => 'Miercoles',
			4 => 'Jueves',
			5 => 'Viernes',
			6 => 'Sabado',
			7 => 'Domingo'
		][$this->dia_semana] ?? null;
	}

	public function barbero()
	{
		return $this->belongsTo(User::class, 'barbero_id');
	}
}
