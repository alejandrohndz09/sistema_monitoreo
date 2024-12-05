<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

/**
 * Class Usuario
 * 
 * @property string $idUsuario
 * @property string $usuario
 * @property string $clave
 * @property int $rol
 * @property string|null $token
 * @property Carbon $fecha_creado
 * @property Carbon|null $fecha_actualizado
 * @property string $idEmpresa
 * @property int $estado
 * 
 * @property Empresa $empresa
 * @property Collection|Evaluacion[] $evaluacions
 *
 * @package App\Models
 */
class Usuario extends Model implements Authenticatable
{
	use AuthenticatableTrait;
	protected $table = 'usuario';
	protected $primaryKey = 'idUsuario';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'rol' => 'int',
		'fecha_creado' => 'datetime',
		'fecha_actualizado' => 'datetime',
		'estado' => 'int'
	];

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'usuario',
		'clave',
		'rol',
		'token',
		'fecha_creado',
		'fecha_actualizado',
		'idEmpresa',
		'estado'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'idEmpresa');
	}

	public function evaluacions()
	{
		return $this->hasMany(Evaluacion::class, 'idUsuario');
	}
}
