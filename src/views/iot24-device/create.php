<?php

/* @var $this yii\web\View */
/* @var $model \matejch\iot24meter\models\Iot24Device */

$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => Yii::t('iot24meter/msg', 'devices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iot24-device-create w-full">

    <h1 class="mt-1 mb-2 text-xl"><?= $this->title ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>