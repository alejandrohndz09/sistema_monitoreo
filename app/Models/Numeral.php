<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Numeral
 * 
 * @property string $idNumeral
 * @property string $nombre
 * @property string|null $descripcion
 * @property string|null $idNumeralPadre
 * @property int $nivel
 * 
 * @property Numeral|null $numeral
 * @property Collection|Monitoreo[] $monitoreos
 * @property Collection|Numeral[] $numerals
 *
 * @package App\Models
 */
class Numeral extends Model
{
	protected $table = 'numeral';
	protected $primaryKey = 'idNumeral';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'nivel' => 'int'
	];

	protected $fillable = [
		'nombre',
		'descripcion',
		'idNumeralPadre',
		'nivel'
	];

	public function numeral()
	{
		return $this->belongsTo(Numeral::class, 'idNumeralPadre');
	}

	public function monitoreos()
	{
		return $this->hasMany(Monitoreo::class, 'idNumeral');
	}

	public function numerales()
	{
		return $this->hasMany(Numeral::class, 'idNumeralPadre');
	}
}
