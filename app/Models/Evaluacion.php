<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Evaluacion
 * 
 * @property string $idEvaluacion
 * @property int|null $estado
 * @property string|null $idUsuario
 * @property string|null $fecha_creado
 * @property Carbon|null $fecha_actualizado
 * 
 * @property Usuario|null $usuario
 * @property Collection|Monitoreo[] $monitoreos
 *
 * @package App\Models
 */
class Evaluacion extends Model
{
	protected $table = 'evaluacion';
	protected $primaryKey = 'idEvaluacion';
	public $incrementing = false;
	public $timestamps = false;
	protected $resultadoC = [];
	protected $resultado = 0;

	protected $casts = [
		'estado' => 'int',
		'fecha_creado' => 'datetime',
		'fecha_actualizado' => 'datetime'
	];

	protected $fillable = [
		'estado',
		'idUsuario',
		'fecha_creado',
		'fecha_actualizado'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'idUsuario');
	}

	public function monitoreos()
	{
		return $this->hasMany(Monitoreo::class, 'idEvaluacion');
	}



	public function resultadoC()
	{
		// Invocar el procedimiento almacenado
		$resultados = DB::select('
		WITH NumeralesPadres AS (
			SELECT idNumeral AS numeralPadre
			FROM numeral
			WHERE nivel = 0
		),
		NumeralesHijos AS (
			SELECT 
				n.idNumeral AS numeralHijo,
				n.idNumeralPadre AS numeralPadre
			FROM 
				numeral n
			WHERE 
				n.idNumeralPadre IN (SELECT numeralPadre FROM NumeralesPadres)
		),
		Conteos AS (
			SELECT 
				nh.numeralPadre AS categoria,
				COUNT(DISTINCT nh.numeralHijo) AS totalconteo,
				SUM(CASE WHEN m.evaluacion = 1 THEN 1 ELSE 0 END) AS respuestas_si,
				SUM(CASE WHEN m.evaluacion = 0 THEN 1 ELSE 0 END) AS respuestas_no,
				SUM(CASE WHEN m.evaluacion = 0.5 THEN 1 ELSE 0 END) AS respuestas_parcial
			FROM 
				monitoreo m
			JOIN 
				NumeralesHijos nh ON m.idNumeral = nh.numeralHijo
			WHERE 
				m.idEvaluacion = ? -- Reemplaza con el valor deseado
			GROUP BY 
				nh.numeralPadre
		)
		SELECT 
			c.categoria,
			(COALESCE(c.respuestas_si, 0) * 1 + 
			COALESCE(c.respuestas_no, 0) * 0 + 
			COALESCE(c.respuestas_parcial, 0) * 0.5) / NULLIF(c.totalconteo, 0) AS total
		FROM 
			Conteos c;
', [$this->idEvaluacion]);

		// Asignar los resultados al arreglo resultadoC
		foreach ($resultados as $resultado) {
			$this->resultadoC[] = $resultado->total;
		}

		return $this->resultadoC;
	}

	public function resultado()
	{
		// $porcentajes = [0.4, 0.09, 0.15, 0.37];
		$porcentajes = [37, 8, 14, 34];


		$this->resultado = 0; // Inicializar el total

		foreach ($this->resultadoC() as $index => $categoria) {
			if (isset($porcentajes[$index])) {
				$this->resultado += $categoria * ($porcentajes[$index] / 93);
			}
		}

		return $this->resultado;
	}
}
