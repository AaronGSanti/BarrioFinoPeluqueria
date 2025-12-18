<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Cita
 * 
 * @property int $id
 * @property int $cliente_id
 * @property int $barbero_id
 * @property int $servicio_id
 * @property Carbon|null $fecha_hora
 * @property Carbon|null $hora_inicio
 * @property Carbon|null $hora_fin
 * @property string|null $estado
 * @property float|null $precio_total
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Servicio $servicio
 *
 * @package App\Models
 */
class Cita extends Model
{
	protected $table = 'citas';

	protected $casts = [
		'cliente_id' => 'int',
		'barbero_id' => 'int',
		'servicio_id' => 'int',
		'fecha_hora' => 'datetime',
		'hora_inicio' => 'datetime',
		'hora_fin' => 'datetime',
		'precio_total' => 'float'
	];

	protected $fillable = [
		'cliente_id',
		'barbero_id',
		'servicio_id',
		'fecha_hora',
		'hora_inicio',
		'hora_fin',
		'estado',
		'precio_total'
	];

	/**Definimos la relacion entre citas y usuarios (clientes) */
	public function cliente()
	{
		return $this->belongsTo(User::class, 'cliente_id');
	}

	/**Definimos la relacion entre citas y servicios */
	public function servicio()
	{
		return $this->belongsTo(Servicio::class);
	}

	/**Definimos esta relacion para que podamos traer el nombre del barbero 
	 * en el buscador de citas.
	 */
	public function barbero()
	{
		return $this->belongsTo(User::class, 'barbero_id');
	}
}
