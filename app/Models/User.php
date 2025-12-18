<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone_number
 * @property string|null $photo_url
 * @property string $role
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|BarberoServicio[] $barbero_servicios
 * @property Collection|Cita[] $citas
 * @property Collection|HorariosBarbero[] $horarios_barberos
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	use HasFactory, Notifiable, HasApiTokens;

	protected $table = 'users';

	protected $casts = [
		'email_verified_at' => 'datetime'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'phone_number',
		'photo_url',
		'role',
		'email_verified_at',
		'password',
		'remember_token'
	];

	public function barbero_servicios()
	{
		return $this->hasMany(BarberoServicio::class, 'barbero_id');
	}

	public function citas()
	{
		return $this->hasMany(Cita::class, 'cliente_id');
	}

	public function horarios_barberos()
	{
		return $this->hasMany(HorariosBarbero::class, 'barbero_id');
	}
}
