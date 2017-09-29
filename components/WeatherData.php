<?php

namespace app\components;

use Yii;
use yii\base\Component;

class WeatherData extends Component {
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
}
