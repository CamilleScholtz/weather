<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use phpnt\chartJS\ChartJs;

$this->title = 'Weather';
?>

<div class="site-index">
	<div class="body-content">
		<?php
		$form = ActiveForm::begin();

		echo Html::activeDropDownList($model, 'station', $model->getStations(), [
			'class' => 'form-control',
			'value' => $model->station,
		]);
		echo '</br>';
		echo DatePicker::widget([
			'model'         => $model,
			'attribute'     => 'start',
			'attribute2'    => 'end',
			'value'         => $model->start,
			'value2'        => $model->end,
			'type'          => DatePicker::TYPE_RANGE,
			'pluginOptions' => [
				'autoclose'      => TRUE,
				'format'         => 'yyyy-mm-dd',
				'todayHighlight' => TRUE,
				'endDate'        => '+1',
			],
		]);
		echo '</br>';
		echo Html::submitButton('Refresh', ['class' => 'btn btn-primary']);

		ActiveForm::end();
	   	?>

		</br></br>

		<?php
		$content = [
			'labels' => $model->date,
			'datasets' => [
				[
					'data'                 => $model->avarage,
					'label'                => 'Avarage temperature',
					'fill'                 => FALSE,
					'borderColor'          => 'rgba(255, 99, 132, 1)',
					'pointBackgroundColor' => 'rgba(255, 99, 132, 1)',
				],
				[
					'data'                 => $model->minimum,
					'label'                => 'Mininmum temperature',
					'fill'                 => FALSE,
					'backgroundColor'      => 'rgba(54, 162, 235, .05)',
					'borderColor'          => 'rgba(54, 162, 235, 1)',
					'pointBackgroundColor' => 'rgba(54, 162, 235, 0)',
					'pointBorderColor'     => 'rgba(54, 162, 235, 0)',
					'borderDash'           => [6, 6],
					'pointRadius'          => 0,
				],
				[
					'data'                 => $model->maximum,
					'label'                => 'Maximum temperature',
					'fill'                 => +1,
					'backgroundColor'      => 'rgba(54, 162, 235, .05)',
					'borderColor'          => 'rgba(54, 162, 235, 1)',
					'pointBackgroundColor' => 'rgba(54, 162, 235, 0)',
					'pointBorderColor'     => 'rgba(54, 162, 235, 0)',
					'borderDash'           => [6, 6],
					'pointRadius'          => 0,
				],
			],
		];

		echo ChartJs::widget([
			'type'    => ChartJs::TYPE_LINE,
			'data'    => $content,
			'options' => [
				 'legend' => [
					 'display' => FALSE,
				 ],
				 'scales' => [
					 'xAxes' => [[
						 'gridLines' => [
							 'color'         => 'rgba(243, 244, 246, 1)',
							 'drawBorder'    => FALSE,
							 'zeroLineColor' => 'rgba(243, 244, 246, 1)',
						 ],
					 ]],
					 'yAxes' => [[
						 'gridLines' => [
							 'color'         => 'rgba(243, 244, 246, 1)',
							 'drawBorder'    => FALSE,
							 'zeroLineColor' => 'rgba(243, 244, 246, 1)',
						 ],
					 ]],
				 ],
			],
		]);
		?>

		</br><hr>

		<a href="<?=Url::to([
			'site/spreadsheet',
			'start'   => $model->start,
			'end'     => $model->end,
			'station' => $model->station,
		]);?>">Download</a> spreadsheet or <a href="https://github.com/onodera-punpun/weather">view</a> source code.
	</div>
</div>
