<?php

namespace matejch\iot24meter\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

class Iot24PriceMapController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['create', 'export', 'import'],
                        'allow' => true, 'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionCreate(): string
    {
        return $this->render('create', []);
    }

    public function actionExport(): Response
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $months = range(1, 12);
        $year = date('Y');
        $calendar = [];
        foreach ($months as $month) {

            $monthName = \DateTime::createFromFormat('!m', $month);
            $monthName = $monthName->format('F');

            for ($d = 1; $d <= 31; $d++) {
                $time = mktime(12, 0, 0, $month, $d, $year);
                if ((int)date('m', $time) === $month) {
                    $calendar[$monthName][$d]['name'] = date('l', $time);
                    $calendar[$monthName][$d]['full_date'] = date('Y-m-d', $time);

                    $startTime = new \DateTime(date('Y-m-d 00:00:00', $time));
                    $endTime = new \DateTime(date('Y-m-d 24:00:00', $time));
                    while ($startTime < $endTime) {
                        $calendar[$monthName][$d]['intervals'][] = $startTime->modify('+15 minutes')->format('H:i:s');
                    }
                }
            }
        }

        foreach ($calendar as $monthName => $days) {

            $worksheet = $spreadsheet->addSheet(new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $monthName));

            $temp = 2;
            foreach ($days[1]['intervals'] as $i => $interval) {
                if ($i === 0) {
                    $worksheet->setCellValue("A{$temp}", $days[1]['intervals'][count($days[1]['intervals']) - 1]);
                    $worksheet->setCellValue("B{$temp}", $interval);
                    continue;
                }

                $worksheet->setCellValue("A{$temp}", $days[1]['intervals'][$i - 1]);
                $worksheet->setCellValue("B{$temp}", $interval);
                $temp++;
            }
        }
        $spreadsheet->removeSheetByIndex(0);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save("calendar.xlsx");

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode("calendar.xlsx") . '"');
        $writer->save('php://output');
        exit();
    }

    public function actionImport(): Response
    {
        return $this->redirect(Yii::$app->request->referrer);
    }
}