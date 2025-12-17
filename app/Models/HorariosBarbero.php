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

	public function user()
	{
		return $this->belongsTo(User::class, 'barbero_id');
	}
}
