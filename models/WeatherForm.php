<?php

namespace app\models;

use Yii;
use yii\base\Model;

class WeatherForm extends Model {
	// The date in yyyy-mm-dd where we want to start gathering weather info.
	public $start;

	// The date in yyyy-mm-dd till where we want to gather weather info.
	public $end;

	// The station we want measurements from.
	public $station;

	// TODO: Validate the given dates.
	public function rules() {
		return [[['start', 'end', 'station'], 'required']];
	}

	public function getStations() {
		return [
			'260' => 'De Bilt',
			'209' => 'IJmond',
			'210' => 'Valkenburg',
			'215' => 'Voorschoten',
			'225' => 'IJmuiden',
			'235' => 'De Kooy',
			'240' => 'Schiphol',
			'242' => 'Vlieland',
			'248' => 'Wijdenes',
			'249' => 'Berkhout',
			'251' => 'Hoorn (Terschelling)',
			'257' => 'Wijk aan Zee',
			'258' => 'Houtribdijk',
			'265' => 'Soesterberg',
			'267' => 'Stavoren',
			'269' => 'Lelystad',
			'270' => 'Leeuwarden',
			'273' => 'Marknesse',
			'275' => 'Deelen',
			'277' => 'Lauwersoog',
			'278' => 'Heino',
			'279' => 'Hoogeveen',
			'280' => 'Eelde',
			'283' => 'Hupsel',
			'285' => 'Huibertgat',
			'286' => 'Nieuw Beerta',
			'290' => 'Twenthe',
			'308' => 'Cadzand',
			'310' => 'Vlissingen',
			'311' => 'Hoofdplaat',
			'312' => 'Oosterschelde',
			'313' => 'Vlakte v.d. Raan',
			'315' => 'Hansweert',
			'316' => 'Schaar',
			'319' => 'Westdorpe',
			'323' => 'Wilhelminadorp',
			'324' => 'Stavenisse',
			'330' => 'Hoek van Holland',
			'331' => 'Tholen',
			'340' => 'Woensdrecht',
			'343' => 'R\'dam-Geulhaven',
			'344' => 'Rotterdam',
			'348' => 'Cabauw',
			'350' => 'Gilze-Rijen',
			'356' => 'Herwijnen',
			'370' => 'Eindhoven',
			'375' => 'Volkel',
			'377' => 'Ell',
			'380' => 'Maastricht',
			'391' => 'Arcen',
		];
	}
}
