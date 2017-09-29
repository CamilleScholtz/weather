<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\WeatherData;
use app\models\WeatherForm;

class SiteController extends Controller {
	public function actions() {
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
		];
	}

	public function actionIndex() {
		$model = new WeatherForm();

		// Load old model (if possible).
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			// TODO.
		} else {
			$model->start = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-
				14, date('Y')));
			$model->end = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'),
				date('Y')));
			$model->station = 260;
		}

		$weather = new WeatherData([
			'start'   => $model->start,
			'end'     => $model->end,
			'station' => $model->station,
		]);
		$weather->GetData();
		$weather->ParseData();

		return $this->render('index', [
			'date'    => $weather->date,
			'avarage' => $weather->avarage,
			'minimum' => $weather->minimum,
			'maximum' => $weather->maximum,
			'model'   => $model,
		]);
	}
}
