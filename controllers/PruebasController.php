<?php

namespace app\controllers;
use Yii;
use app\models\SprintRequerimientosSearch2;
use kartik\mpdf\Pdf;

class PruebasController extends \yii\web\Controller
{
    public function actionIndex()
    {

        $searchModel = new SprintRequerimientosSearch2();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=30;
        

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        
        
    }
    
    
public function actionReport() {
 
        // get your HTML raw content without any layouts or scripts
        $content = "
            <b style='color:red'>FRANCISCO ANDRES ORTEGA FLOREZ<</b>
            ";
         
        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content, 
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
             // call mPDF methods on the fly
            'methods' => [
                'SetHeader'=>['THIS IS REPORT'],
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);
 
        // http response
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
 
        // return the pdf output as per the destination setting
        return $pdf->render();
    }
    
    

}
