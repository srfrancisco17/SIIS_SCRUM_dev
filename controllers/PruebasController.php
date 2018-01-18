<?php

namespace app\controllers;
use Yii;
use app\models\SprintRequerimientosSearch2;

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

}
