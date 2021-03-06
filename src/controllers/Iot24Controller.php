<?php

namespace matejch\iot24meter\controllers;

use matejch\iot24meter\models\Iot24Device;
use matejch\iot24meter\models\Iot24Search;
use matejch\iot24meter\services\ConsumptionStatistics;
use matejch\iot24meter\services\SensorDataLoader;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class Iot24Controller extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'load', 'update'],
                        'allow' => true, 'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $searchModel = new Iot24Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $get = Yii::$app->request->get();
        $rawData = \matejch\iot24meter\models\Iot24::getRawData($get);

        $statisticsService = new ConsumptionStatistics($rawData);

        if (isset($get['device']) && !empty($get['device'])) {
            $defaultSearchDevice = Iot24Device::find()->where(['device_id' => $get['device']])->active()->one();
        } else {
            $defaultSearchDevice = Iot24Device::find()->where(['id' => 1])->active()->one();
        }

        $defaultSearchChannels = [];
        if ($defaultSearchDevice) {
            $defaultSearchChannels = Json::decode($defaultSearchDevice->aliases);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'series' => $statisticsService->parse($get),
            'dates' => $statisticsService->getDates(),
            'device' => $defaultSearchDevice,
            'channels' => $defaultSearchChannels,
            'devices' => ArrayHelper::map(Iot24Device::find()->active()->select(['device_id', 'device_name'])->all(), 'device_id', 'device_name'),
        ]);
    }

    public function actionLoad(): Response
    {
        $message = '';
        /** @var Iot24Device $device */
        foreach (Iot24Device::find()->active()->each(10) as $device) {
            $service = new SensorDataLoader($device);

            foreach ($service->get() as $item) {
                $model = new \matejch\iot24meter\models\Iot24();
                $result = $model->upsert($item);

                if ($result) {
                    $message = Yii::t('iot24meter/msg', 'save_success_msg') . "<br>";
                } else {
                    $message = Yii::t('iot24meter/msg', 'save_fail_msg') . "<br>";
                }
            }

            $device->update();
        }

        Yii::$app->session->setFlash('info', $message);
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function findModel($id): ?\matejch\iot24meter\models\Iot24
    {
        if (($model = \matejch\iot24meter\models\Iot24::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('iot24meter/msg', 'Not found'));
    }
}