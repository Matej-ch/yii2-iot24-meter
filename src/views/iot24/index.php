<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Json;

/* @var $series array */
/* @var $dates array */
/* @var $devices array */
/* @var $channels array */
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \matejch\iot24meter\models\Iot24Search */
/* @var $device \matejch\iot24meter\models\Iot24Device */

$this->title = Yii::t('iot24meter/msg', 'iot');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="iot-index mt-20 w-full px-4">

    <h1 class="mt-1 mb-2 text-xl"><?= $this->title ?></h1>

    <p>
        <?= Html::a(Yii::t('iot24meter/msg', 'add_device'), ['device/index'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('iot24meter/msg', 'notifications'), ['subscriber/index'], ['class' => 'btn btn-warning']) ?>
        <?= Html::a(Yii::t('iot24meter/msg', 'load'), ['load'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('iot24meter/msg', 'create_price_map'), ['iot24-price-map-data/index'], ['class' => 'btn btn-default']) ?>
    </p>

    <?= $this->render('partials/_graph', ['series' => $series, 'dates' => $dates, 'devices' => $devices, 'device' => $device, 'channels' => $channels]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'system_id',
            'device_id',
            'device_type' => [
                'attribute' => 'device_type',
                'format' => 'raw',
                'value' => static function ($model) {
                    return $model->device->device_name;
                },
                'filter' => Html::activeDropDownList($searchModel, 'device_type', $devices, ['class' => 'form-control', 'prompt' => Yii::t('iot24meter/msg', 'choose')]),
            ],
            'increments' => [
                'attribute' => 'increments',
                'format' => 'raw',
                'value' => static function ($model) {
                    $increments = Json::decode($model->increments);

                    $aliases = $model->device->aliases;
                    if (!empty($aliases)) {
                        $aliases = Json::decode($aliases);
                    }
                    $values = Json::decode($model->values);

                    $html = '<div class="flex-container">';

                    foreach ($increments as $key => $increment) {
                        $letter = str_replace('kanal', '', $key);
                        $html .= "<div class='pb'><span class='font-bold'>" . ($aliases[$key] ?? $key) . ":</span> " . $values["value$letter"] . "<span class='font-bold'>(+$increment) [watt]</span></div>";
                    }
                    $html .= '</div>';

                    return $html;
                },
            ],
            'status' => [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => static function ($model) {
                    return $model->getStatuses()[$model->status] ?? '';
                },
                'filter' => Html::activeDropDownList($searchModel, 'status', $searchModel->getStatuses(), ['class' => 'form-control', 'prompt' => Yii::t('iot24meter/msg', 'choose')]),
            ],
            'created_at' => [
                'attribute' => 'created_at',
                'format' => 'raw',
                'value' => static function ($model) {
                    $created = "<div><span class='font-bold'>" . $model->getAttributeLabel('created_at') . ":</span> $model->created_at</div>";
                    $updated = "<div><span class='font-bold'>" . $model->getAttributeLabel('updated_at') . ":</span> $model->updated_at</div>";
                    return $created . $updated;
                }
            ],
            'updated_by'
        ],
    ]) ?>
</div>