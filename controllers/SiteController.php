<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\WeatherModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SiteController extends Controller {
	public function actions() {
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
		];
	}

	public function actionIndex() {
		// Create model and try to load data from POST request, in case there is
		// no POST request the model will be filled with default values.
		$model = new WeatherModel();
		if (!$model->load(Yii::$app->request->post())) {
			$model->start = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-
				14, date('Y')));
			$model->end = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'),
				date('Y')));
			$model->station = 260;
		}

		// Gather and parse weather data.
		$model->getData();
		$model->parseData();

		return $this->render('index', ['model' => $model]);
	}

	public function actionSpreadsheet($start, $end, $station) {
		$model = new WeatherModel();
		$model->start = $start;
		$model->end = $end;
		$model->station = $station;

		// Gather and parse weather data.
		$model->getData();
		$model->parseData();

		$xlsl = new Spreadsheet();
		$xlsl->getProperties()->setCreator('Camille Scholtz')->setTitle(
			'Weather');

		// Fill spreadsheet with content.
		$content = [
			['Date', 'Avarage temperature', 'Minimum temperature', 'Maximum temperature'],
		];
		for ($i=0; $i<count($model->date); $i++) {
			$content[] = [$model->date[$i], $model->avarage[$i],
				$model->minimum[$i], $model->maximum[$i]];
		}
		$xlsl->getActiveSheet()->fromArray($content, NULL, 'A1');

		// Resize columns.
		foreach (range('A', 'D') as $id) {
			$xlsl->getActiveSheet()->getColumnDimension($id)->setAutoSize(TRUE);
		}

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="spreadsheet.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = IOFactory::createWriter($xlsl, 'Xlsx');
		$writer->save('php://output');

		return $this->render('spreadsheet');
	}
}
