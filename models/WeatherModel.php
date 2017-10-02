<?php

namespace app\models;

use Yii;
use yii\base\Model;

class WeatherModel extends Model {
	public $id;

	// The date in yyyy-mm-dd where we want to start gathering weather info.
	public $start;

	// The date in yyyy-mm-dd till where we want to gather weather info.
	public $end;

	// The station we want measurements from.
	public $station;

	// An array with the dates of the measurements.
	public $date;

	// An array with avarage temperatures.
	public $avarage;

	// An array with minimum temperatures.
	public $minimum;

	// An array with maximum temperatures.
	public $maximum;

	// Raw weather data, this is unparsed and still includes garbage such as
	// comments.
	private $data;

	public function rules() {
		return [
			[['start', 'end', 'station'], 'required'],
		];
	}

	public function getData() {
		$client = new \GuzzleHttp\Client([
			'base_uri' => 'http://projects.knmi.nl/klimatologie/',
		]);

		$response = $client->request('POST',
			'daggegevens/getdata_dag.cgi', [
			'form_params' => [
				'start' => date('Ymd', strtotime($this->start)),
				'end'   => date('Ymd', strtotime($this->end)),
				'vars'  => 'TEMP',
				'stns'  => $this->station,
			],
		]);

		$this->data = $response->getBody();
	}

	public function parseData() {
		foreach (explode("\n", $this->data) as $line) {
			// Ignore comments.
			if (strpos($line, '#') === 0) {
				continue;
			}

			// Transform the line into a nice usable array.
			$array = explode(',', $line);
			if (count($array) < 3) {
				continue;
			}
			array_walk($array, function (&$v) {
				$v = trim($v);
			});

			$this->date[] = date('Y-m-d', strtotime($array[1]));
			$this->avarage[] = $array[2]/10;
			$this->minimum[] = $array[3]/10;
			$this->maximum[] = $array[5]/10;
		}
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
