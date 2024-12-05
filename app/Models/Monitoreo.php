<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Monitoreo
 * 
 * @property string $idMonitoreo
 * @property string $idEvaluacion
 * @property string $idNumeral
 * @property float $evaluacion
 * @property Carbon $fecha_creado
 * @property Carbon|null $fecha_actualizado
 * 
 * @property Numeral $numeral
 *
 * @package App\Models
 */
class Monitoreo extends Model
{
	protected $table = 'monitoreo';
	protected $primaryKey = 'idMonitoreo';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'evaluacion' => 'float',
		'fecha_creado' => 'datetime',
		'fecha_actualizado' => 'datetime'
	];

	protected $fillable = [
		'idEvaluacion',
		'idNumeral',
		'evaluacion',
		'fecha_creado',
		'fecha_actualizado'
	];

	public function evaluacion()
	{
		return $this->belongsTo(Evaluacion::class, 'idEvaluacion');
	}

	public function numeral()
	{
		return $this->belongsTo(Numeral::class, 'idNumeral');
	}
}
