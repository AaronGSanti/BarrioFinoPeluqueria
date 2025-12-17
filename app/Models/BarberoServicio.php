<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BarberoServicio
 * 
 * @property int $id
 * @property int $barbero_id
 * @property int $servicio_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Servicio $servicio
 *
 * @package App\Models
 */
class BarberoServicio extends Model
{
	protected $table = 'barbero_servicio';

	protected $casts = [
		'barbero_id' => 'int',
		'servicio_id' => 'int'
	];

	protected $fillable = [
		'barbero_id',
		'servicio_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'barbero_id');
	}

	public function servicio()
	{
		return $this->belongsTo(Servicio::class);
	}
}
