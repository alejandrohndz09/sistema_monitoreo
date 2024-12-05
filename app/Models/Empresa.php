<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Empresa
 * 
 * @property string $idEmpresa
 * @property string $nombre
 * @property string|null $direccion
 * @property string $correo
 * @property Carbon $fecha_creado
 * @property Carbon|null $fecha_actualizado
 * @property int $estado
 * 
 * @property Collection|Usuario[] $usuarios
 *
 * @package App\Models
 */
class Empresa extends Model
{
	protected $table = 'empresa';
	protected $primaryKey = 'idEmpresa';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'fecha_creado' => 'datetime',
		'fecha_actualizado' => 'datetime',
		'estado' => 'int'
	];

	protected $fillable = [
		'nombre',
		'direccion',
		'correo',
		'fecha_creado',
		'fecha_actualizado',
		'estado'
	];

	public function usuarios()
	{
		return $this->hasMany(Usuario::class, 'idEmpresa');
	}
}
