<?php

use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('iot24meter/msg', 'devices');
$this->params['breadcrumbs'][] = ['label' => Yii::t('iot24meter/msg', 'iot'), 'url' => ['iot24/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iot-index mt-20 w-full px-4">

    <h1 class="mt-1 mb-2 text-xl"><?= $this->title ?></h1>

    <p>
        <?= Html::a(Yii::t('iot24meter/msg', 'create'), ['create'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => static function ($model, $key, $index, $grid) {
            if (!$model->is_active) {
                return ['style' => 'background-color:#d6d6d6;text-decoration:line-through'];
            }
        },
        'columns' => [
            [
                'class' => ActionColumn::class,
                'template' => '{update} {delete}',
            ],
            'id',
            'endpoint',
            'device_id',
            'device_name',
            'device_type_id',
            'device_type_name',
            'refresh_interval_minutes',
            'pulse_frequency',
            'aliases',
            'created_at' => [
                'attribute' => 'created_at',
                'format' => 'raw',
                'value' => static function ($model) {
                    $created = "<div><span class='font-bold'>" . $model->getAttributeLabel('created_at') . ":</span> $model->created_at</div>";
                    $updated = "<div><span class='font-bold'>" . $model->getAttributeLabel('updated_at') . ":</span> $model->updated_at</div>";
                    return $created . $updated;
                }
            ],
        ],
    ]) ?>
</div>