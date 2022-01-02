<?php

use matejch\iot24meter\enums\Device;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \matejch\iot24meter\models\Iot24Search */

$this->title = Yii::t('iot24meter/msg', 'iot');
?>
<div class="iot-index mt-20 w-full px-4">

    <h1 class="mt-1 mb-2 text-xl"><?= $this->title ?></h1>

    <p>
        <?= Html::a(Yii::t('iot24meter/msg', 'load'), ['load'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
            ],
            'system_id',
            'device_id',
            'device_type' => [
                'attribute' => 'device_type',
                'format' => 'raw',
                'value' => static function ($model) {
                    return Device::getList()[$model->device_type] ?? '';
                },
                'filter' => Html::activeDropDownList($searchModel, 'device_type', Device::getList(), ['class' => 'form-control', 'prompt' => Yii::t('iot24meter/msg', 'choose')]),
            ],
            'increments' => [
                'attribute' => 'increments',
                'format' => 'raw',
                'value' => static function ($model) {
                    $increments = Json::decode($model->increments);
                    $values = Json::decode($model->values);

                    $html = '<div class="flex-container">';
                    foreach ($increments as $key => $increment) {
                        $letter = str_replace('kanal', '', $key);
                        $html .= "<div class='pb'><span class='font-bold'>" . ucfirst($key) . ":</span> " . $values["value$letter"] . "<span class='font-bold'>(+$increment) [watt]</span></div>";
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
                    $created = "<div><span class='font-bold'>".$model->getAttributeLabel('created_at').":</span> $model->created_at</div>";
                    $updated = "<div><span class='font-bold'>".$model->getAttributeLabel('updated_at').":</span> $model->updated_at</div>";
                    return $created . $updated;
                }
            ],
            'updated_by'
        ],
    ]) ?>
</div>