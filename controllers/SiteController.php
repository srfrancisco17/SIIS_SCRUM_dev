<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\web\Response;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SprintRequerimientos;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Usuarios;
use app\models\Sprints;
use app\models\SprintUsuarios;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index-scrum-master', 'index-developer','login','logout','contact'],
                'rules' => [
                    [
                        'actions' => ['logout','contact', 'index-developer'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index-scrum-master', 'about','logout'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $validar_tipousuario = [Usuarios::USUARIO_SCRUM_MASTER || Usuarios::USUARIO_PRODUCT_OWNER];
                            return Usuarios::tipoUsuarioArreglo($validar_tipousuario) && Usuarios::estaActivo();
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    
    
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    /**
     * Login action.
     *
     * @return Response|string
     */
    
    public function actionLogin(){
         
        if (!Yii::$app->user->isGuest) {

            $tipo_usuario = Yii::$app->user->identity->tipo_usuario;

            switch ($tipo_usuario) {

                case Usuarios::USUARIO_SCRUM_MASTER:

                    return $this->redirect(["site/index-scrum-master"]);

                case Usuarios::USUARIO_DEVELOPER:

                    return $this->redirect(["sprint-usuarios/index"]); 
                    
                case Usuarios::USUARIO_PRODUCT_OWNER:

                    return $this->redirect(["site/index-scrum-master"]); 

            }

        }
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            $tipo_usuario = Yii::$app->user->identity->tipo_usuario;
            
            $session = Yii::$app->getSession();
            $session->set('skin_session', Yii::$app->user->identity->skin);

            switch ($tipo_usuario) {

                case Usuarios::USUARIO_SCRUM_MASTER:

                    return $this->redirect(["site/index-scrum-master"]);

                case Usuarios::USUARIO_DEVELOPER:

                    return $this->redirect(["sprint-usuarios/index"]); 
                    
                case Usuarios::USUARIO_PRODUCT_OWNER:

                    return $this->redirect(["site/index-scrum-master"]);     
                
            }        
        }else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        //return $this->goHome();
        return $this->redirect(['site/login']);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
 
    
    public function actionIndexScrumMaster()
    {
        
        /*
         * Modificacion 06/10/2017 
         * SCRUM_MASTER
         * Cambio pendiente limpiar y purificar el codigo y reutilizable
         */
        
        $datos = NULL;
        
        
        $obj_sprint = NULL;
        
        if (is_null(Yii::$app->request->post("sprint_id"))){
            
            /*
             * Si no llega por POST el dato de sprint_id 
             * Por defecto tomara el ultimo sprint
             */
            
            $obj_sprint = Sprints::find()->orderBy(['sprint_id'=>SORT_DESC])->asArray()->limit(1)->one();
            
        }else{
            /*
             * Si Llega por POST el sprint id se busca especificamente ese sprint
             */
            
            $obj_sprint = Sprints::find()->where(['sprint_id' => Yii::$app->request->post("sprint_id")])->asArray()->one();
  
        }
        
        $sprint_id = $obj_sprint['sprint_id'];
        
        //var_dump($sprint_id);exit;
       
        $array_sprints_usuarios = SprintUsuarios::find()->where(['sprint_id'=>$sprint_id])->andWhere(['estado'=>'1'])->all();

        $whereUsuario = "";
        $usuario_id = Yii::$app->request->post('list');
        //$titulo = '';
        $subtitulo = '---';
        
        
        if(empty($usuario_id)){
            
            // Diagrama De Todos Los Usuarios

            $datos['total_tiempo_calculado'] = SprintRequerimientos::find()->joinWith('requerimiento')->where(['sprint_id' => $sprint_id])->sum('requerimientos.tiempo_desarrollo');
            
            $datos['titulo'] = 'Total horas del grupo = '.$obj_sprint['horas_desarrollo']." Horas";
            
        }else{
            
            // Diagrama Por Usuario
            
            
            
            $datos['total_tiempo_calculado'] = SprintRequerimientos::find()->joinWith('requerimiento')->where(['sprint_id' => $sprint_id])->andWhere(['usuario_asignado' => $usuario_id])->sum('requerimientos.tiempo_desarrollo');
            $whereUsuario= "and sr.usuario_asignado = ".$usuario_id." ";
            
            $datos['titulo'] = "Total Horas = ".$datos['total_tiempo_calculado']." Horas";
           
        }
            
        $connection = Yii::$app->db;
        
        $datos['consulta_acutal_burn'] = $connection->createCommand("select 
                                            sum(rt.horas_desarrollo) as sum_horas,
                                            rt.fecha_terminado::date
                                            from sprint_requerimientos as sr
                                            inner join sprint_requerimientos_tareas as srt
                                            on (
                                                    srt.requerimiento_id = sr.requerimiento_id
                                                    and srt.sprint_id = sr.sprint_id
                                            )
                                            inner join requerimientos_tareas as rt
                                            on (
                                                rt.requerimiento_id = srt.requerimiento_id
                                                and rt.tarea_id = srt.tarea_id
                                            )
                                            where sr.sprint_id = :sprint_id
                                            $whereUsuario
                                            and srt.estado = '4'
                                            group by rt.fecha_terminado::date
                                            order by rt.fecha_terminado::date")
                                            ->bindValue(':sprint_id', $sprint_id)
                                            ->queryAll(); 
        
        $datos['subtitulo'] = $obj_sprint['sprint_alias'].' | ('.$obj_sprint['fecha_desde'].') - ('.$obj_sprint['fecha_hasta'].')';

        $consulta_total_tareas = $connection->createCommand("
            select 
                COUNT(*)
                from sprint_requerimientos as sr
                inner join sprint_requerimientos_tareas as srt
                on (
                    srt.requerimiento_id = sr.requerimiento_id
                    and srt.sprint_id = sr.sprint_id
                    )
                where sr.sprint_id = :sprint_id
                $whereUsuario
        ")
        ->bindValue(':sprint_id', $sprint_id)
        ->queryScalar(); 
        
        $consulta_total_tareas_terminadas = $connection->createCommand("
            select 
                COUNT(*)
                from sprint_requerimientos as sr
                inner join sprint_requerimientos_tareas as srt
                on (
                    srt.requerimiento_id = sr.requerimiento_id
                    and srt.sprint_id = sr.sprint_id
                    )
                where sr.sprint_id = :sprint_id
                $whereUsuario
                and srt.estado = '4' ")
        ->bindValue(':sprint_id', $sprint_id)
        ->queryScalar();        
        
        
        
        $consulta_total_requerimientos = $connection->createCommand("
            select 
                COUNT(*)
                from sprint_requerimientos as sr
                where sprint_id = :sprint_id
                $whereUsuario   
        ")
        ->bindValue(':sprint_id', $sprint_id)
        ->queryScalar();
  
        
        $consulta_total_requerimientos_terminados = $connection->createCommand("
            select 
                COUNT(*)
                from sprint_requerimientos as sr
                where sprint_id = :sprint_id
                $whereUsuario
                and estado = '4' 
        ")
        ->bindValue(':sprint_id', $sprint_id)
        ->queryScalar();   
        
        if ($consulta_total_tareas != 0){
            $porcentaje_tareas =  number_format($consulta_total_tareas_terminadas*100/$consulta_total_tareas, 2);
        }else{
            $porcentaje_tareas = 0;
        }
        
        if ($consulta_total_requerimientos_terminados != 0){
            $porcentaje_requerimientos =  number_format($consulta_total_requerimientos_terminados*100/$consulta_total_requerimientos, 2); 
        }else{
            $porcentaje_requerimientos = 0; 
        }
        
        
        
        
        //Porcentaje Span Requerimientos
        
        $html_span_requerimientos = "";
        
        if($porcentaje_requerimientos == 0){
            $html_span_requerimientos = '<span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> '.$porcentaje_requerimientos.'%</span>'; 
        }
        else if ($porcentaje_requerimientos <= 30){

            $html_span_requerimientos = '<span class="description-percentage text-red"><i class="fa fa-caret-down"></i> '.$porcentaje_requerimientos.'%</span>'; 
        
            
        }else if ($porcentaje_requerimientos > 30) {
            $html_span_requerimientos = '<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> '.$porcentaje_requerimientos.'%</span>'; 
        }
        
        
        //Porcentaje Span tareas
        
        $html_span_tareas = "";
        
        if($porcentaje_tareas == 0){
            $html_span_tareas = '<span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> '.$porcentaje_tareas.'%</span>'; 
        }
        else if ($porcentaje_tareas <= 30){

            $html_span_tareas = '<span class="description-percentage text-red"><i class="fa fa-caret-down"></i> '.$porcentaje_tareas.'%</span>'; 
            
        }else if ($porcentaje_tareas > 30) {
            $html_span_tareas = '<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> '.$porcentaje_tareas.'%</span>'; 
        }

        $barChart = $connection->createCommand(" 
            SELECT
                    usu.usuario_id,
                    usu.nombres,
                    usu.apellidos,
                    sprusu.horas_establecidas,
                    (
                            SELECT
                                    SUM( rt1.horas_desarrollo )
                            FROM
                                    sprint_requerimientos AS sr1
                            LEFT JOIN sprint_requerimientos_tareas srt1 ON
                                    (
                                            sr1.sprint_id = srt1.sprint_id
                                            AND sr1.requerimiento_id = srt1.requerimiento_id
                                    )
                            LEFT JOIN requerimientos_tareas AS rt1 ON
                                    (
                                            srt1.requerimiento_id = rt1.requerimiento_id
                                            AND rt1.tarea_id = srt1.tarea_id
                                    )
                            WHERE
                                    sr1.sprint_id = ".$sprint_id."
                                    AND srt1.estado = '4'
                                    AND sr1.usuario_asignado = usu.usuario_id
                            GROUP BY
                                    sr1.usuario_asignado
                    ) AS tiempo_terminado
            FROM
                    sprint_requerimientos AS sr

            INNER JOIN sprint_usuarios AS sprusu ON
                    (
                            sprusu.sprint_id = sr.sprint_id
                    )	
            INNER JOIN usuarios AS usu ON
                    (
                            usu.usuario_id = sr.usuario_asignado
                    )
            WHERE
                    sr.sprint_id = $sprint_id
            GROUP BY
                    1,
                    2,
                    3,
                    sr.usuario_asignado,
                    sprusu.horas_establecidas
            ORDER BY
                    usu.usuario_id DESC
        ")->queryAll();        
        
        return $this->render('indexScrumMaster', [
            
            'datos' => $datos,
            //'array_actual' => $array_actual,
            //'total_tiempo_calculado' => $total_tiempo_calculado,
            //'consulta_acutal_burn' => $consulta_acutal_burn,
            //'titulo' => $titulo,
            //'subtitulo' => $subtitulo,
            'consulta_total_requerimientos' => $consulta_total_requerimientos,
            'consulta_total_requerimientos_terminados' => $consulta_total_requerimientos_terminados,
            'consulta_total_tareas' => $consulta_total_tareas,
            'consulta_total_tareas_terminadas' => $consulta_total_tareas_terminadas,
            'porcentaje_requerimientos' => $porcentaje_requerimientos,
            'html_span_requerimientos' => $html_span_requerimientos,
            'html_span_tareas' => $html_span_tareas,
            //'array_sprints' => $array_sprints,
            'obj_sprint' => $obj_sprint,
            'array_sprints_usuarios' => $array_sprints_usuarios,
            'barChart' => $barChart,
        ]);
    }    
    
    
    
    
    public function actionIndexDeveloper()
    {
        
        /*
         * Modificacion 05/10/2017 
         *
         */
        $obj_sprint = "";
        
        if (is_null(Yii::$app->request->post("sprint_id"))){
            
            /*
             * Si no llega por POST el dato de sprint_id 
             * Por defecto tomara el ultimo sprint
             */
            
            $obj_sprint = Sprints::find()->orderBy(['sprint_id'=>SORT_DESC])->asArray()->limit(1)->one();
            
        }else{
            /*
             * Si Llega por POST el sprint id se busca especificamente ese sprint
             */
            
            $obj_sprint = Sprints::find()->where(['sprint_id' => Yii::$app->request->post("sprint_id")])->asArray()->one();
        }
        
        $sprint_id = $obj_sprint['sprint_id'];
        $usuario_id = Yii::$app->user->identity->usuario_id;
        
        $obj_sprint_usuario = SprintUsuarios::find()->where(['sprint_id' => $sprint_id])->andWhere(['usuario_id' => $usuario_id])->asArray()->one();
        
        //$consulta_ideal_burn = SprintRequerimientos::findOne(['sprint_id' => $sprint_id, 'usuario_asignado' => $usuario_id]);
        
        
        $total_tiempo_calculado = SprintRequerimientos::find()->joinWith('requerimiento')->where(['sprint_id' => $sprint_id])->andWhere(['usuario_asignado' => $usuario_id])->sum('requerimientos.tiempo_desarrollo');
    
        /*
        echo '<pre>';
        var_dump($consulta_ideal_burn);
        echo '</pre>';
        exit; 
        */
        
        $connection = Yii::$app->db;
        $consulta_acutal_burn = $connection->createCommand("select 
                                            sum(rt.horas_desarrollo) as sum_horas,
                                            rt.fecha_terminado::date
                                            from sprint_requerimientos as sr
                                            inner join sprint_requerimientos_tareas as srt
                                            on (
                                                    srt.requerimiento_id = sr.requerimiento_id
                                                    and srt.sprint_id = sr.sprint_id
                                            )
                                            inner join requerimientos_tareas as rt
                                            on (
                                                rt.requerimiento_id = srt.requerimiento_id
                                                and rt.tarea_id = srt.tarea_id
                                            )
                                            where sr.sprint_id = :sprint_id
                                            and sr.usuario_asignado = :usuario_asignado
                                            and srt.estado = '4'

                                            group by rt.fecha_terminado::date
                                            order by rt.fecha_terminado::date")
                                            ->bindValue(':sprint_id', $sprint_id)
                                            ->bindValue(':usuario_asignado', $usuario_id)
                                            ->queryAll();        
         
        $consulta_total_tareas = $connection->createCommand("select 
                                                            COUNT(*)
                                                            from sprint_requerimientos as sr
                                                            inner join sprint_requerimientos_tareas as srt
                                                            on (
                                                                srt.requerimiento_id = sr.requerimiento_id
                                                                and srt.sprint_id = sr.sprint_id
                                                            )
                                                            where sr.sprint_id = :sprint_id
                                                            and sr.usuario_asignado = :usuario_asignado")
                                                    ->bindValue(':sprint_id', $sprint_id)
                                                    ->bindValue(':usuario_asignado', $usuario_id)
                                                    ->queryScalar(); 
        
        $consulta_total_tareas_terminadas = $connection->createCommand("select 
                                                            COUNT(*)
                                                            from sprint_requerimientos as sr
                                                            inner join sprint_requerimientos_tareas as srt
                                                            on (
                                                                srt.requerimiento_id = sr.requerimiento_id
                                                                and srt.sprint_id = sr.sprint_id
                                                            )
                                                            where sr.sprint_id = :sprint_id
                                                            and sr.usuario_asignado = :usuario_asignado
                                                            and srt.estado = '4' ")
                                                    ->bindValue(':sprint_id', $sprint_id)
                                                    ->bindValue(':usuario_asignado', $usuario_id)
                                                    ->queryScalar(); 

        $consulta_total_requerimientos = $connection->createCommand("select 
                                                                    COUNT(*)
                                                                    from sprint_requerimientos as sr
                                                                    where sprint_id = :sprint_id
                                                                    and usuario_asignado = :usuario_asignado")
                                            ->bindValue(':sprint_id', $sprint_id)
                                            ->bindValue(':usuario_asignado', $usuario_id)
                                            ->queryScalar(); 
                  
        $consulta_total_requerimientos_terminados = $connection->createCommand("select 
                                                                    COUNT(*)
                                                                    from sprint_requerimientos as sr
                                                                    where sprint_id = :sprint_id
                                                                    and usuario_asignado = :usuario_asignado
                                                                    and estado = '4' ")
                                            ->bindValue(':sprint_id', $sprint_id)
                                            ->bindValue(':usuario_asignado', $usuario_id)
                                            ->queryScalar(); 
        
        
        $barChart = $connection->createCommand(" 

            select
                    usu.usuario_id, usu.nombres, usu.apellidos, sum(r.tiempo_desarrollo) as tiempo_total,
                    (
                            select
                                    sum(rt1.horas_desarrollo)
                            from sprint_requerimientos as sr1
                            left join sprint_requerimientos_tareas srt1
                            on(
                                    sr1.sprint_id = srt1.sprint_id
                                    and
                                    sr1.requerimiento_id = srt1.requerimiento_id
                            )
                            left join requerimientos_tareas as rt1
                            on(
                                    srt1.requerimiento_id = rt1.requerimiento_id
                                    and rt1.tarea_id = srt1.tarea_id
                            )
                            where sr1.sprint_id = ".$sprint_id." and srt1.estado = '4' and sr1.usuario_asignado = ".$usuario_id."
                            
                    ) as tiempo_terminado
            from
                    sprint_requerimientos as sr
            inner join requerimientos as r
            on (
                    r.requerimiento_id = sr.requerimiento_id
            )
            inner join usuarios as usu
            on (
                    usu.usuario_id = sr.usuario_asignado
            )
            where
                    sr.sprint_id = ".$sprint_id." and usuario_asignado = ".$usuario_id."
            group by 1, 2, 3, sr.usuario_asignado            

        ")->queryOne();
        
        return $this->render('indexDeveloper',[
            //'consulta_ideal_burn' => $consulta_ideal_burn,
            'total_tiempo_calculado' => $total_tiempo_calculado,
            'consulta_acutal_burn' => $consulta_acutal_burn,
            'consulta_total_requerimientos' => $consulta_total_requerimientos,
            'consulta_total_requerimientos_terminados' => $consulta_total_requerimientos_terminados,
            'consulta_total_tareas' => $consulta_total_tareas,
            'consulta_total_tareas_terminadas' => $consulta_total_tareas_terminadas,
            'obj_sprint' => $obj_sprint,
            'barChart' => $barChart,
            'obj_sprint_usuario' => $obj_sprint_usuario,
        ]);
    }
}
