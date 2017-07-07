<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/Proyecto-FAOF2/web/img/icono-cdo.png" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>
                    <?php
                        if (Yii::$app->user->isGuest) {
                            echo 'Soy Desconocido';
                        }else{
                            echo Yii::$app->user->identity->nombres;
                        }
                    ?>
                </p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Buscar..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    [
                        'label' => 'Dashboard',
                        'icon' => 'dashboard ',
                        'url' => ['site/index-scrum-master'],
                        'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->tipo_usuario == \app\models\Usuarios::USUARIO_SCRUM_MASTER,
                    ],
                    [
                        'label' => 'Gii',
                        'icon' => 'file-code-o',
                        'url' => ['/gii'],
                        'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->tipo_usuario == \app\models\Usuarios::USUARIO_SCRUM_MASTER,
                    ],
                    [
                        'label' => 'Comites', 
                        'icon' => 'book', 
                        'url' => ['comites/index'],
                        'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->tipo_usuario == \app\models\Usuarios::USUARIO_SCRUM_MASTER,
                    ],
                    [
                        'label' => 'Requerimientos',
                        'icon' => 'check-square-o',
                        'url' => ['requerimientos/index'],
                        'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->tipo_usuario == \app\models\Usuarios::USUARIO_SCRUM_MASTER ||\app\models\Usuarios::USUARIO_DEVELOPER,
                    ],
                    [
                        'label' => 'Sprints',
                        'icon' => 'undo',
                        'url' => ['sprints/index'],
                        'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->tipo_usuario == \app\models\Usuarios::USUARIO_SCRUM_MASTER,
                    ],
                    [
                        'label' => 'Diagramas',
                        'icon' => 'folder-open',
                        'url' => ['#'],
                        'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->tipo_usuario == \app\models\Usuarios::USUARIO_SCRUM_MASTER,
                        'items' => [
                            //['label' => 'Burndown', 'icon'=>'line-chart', 'url' => ['diagramas/burndown2']],
                            ['label' => 'Gantt', 'icon'=>'align-left', 'url' => ['diagramas/gantt']],
                            //['label' => 'Burndown', 'icon'=>'line-chart', 'url' => ['diagramas/burndown']],
                            ['label' => 'area-chart', 'icon'=>'area-chart', 'url' => ['#']],
                            ['label' => 'bar-chart', 'icon'=>'bar-chart', 'url' => ['#']],
                        ],
                    ],
                    //['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    [
                        'label' => 'Administracion', 
                        'icon' => 'windows',
                        'url' => ['#'],
                        'items' => [
                            ['label' => 'Usuarios', 'icon'=>'users', 'url' => ['usuarios/index']],
                            ['label' => 'Tipo Usuarios', 'icon'=>'user-secret', 'url' => ['tipos-usuarios/index']],
                            ['label' => 'Tipo Documentos', 'icon'=>'folder', 'url' => ['tipos-documentos/index']],
                            ['label' => 'Departamentos', 'icon'=>'building', 'url' => ['departamentos/index']],
                        ],
                        'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->tipo_usuario == \app\models\Usuarios::USUARIO_SCRUM_MASTER,
                    ],
                    //Items Del Usuario De Tipo Desarrollador
                    [
                        'label' => 'Dashboard',
                        'icon' => 'dashboard ',
                        'url' => ['site/index-developer'],
                        'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->tipo_usuario == \app\models\Usuarios::USUARIO_DEVELOPER,
                    ],
                    [
                        'label' => 'Mis Sprints',
                        'icon' => 'undo',
                        'url' => ['sprint-usuarios/index'],
                        'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->tipo_usuario == \app\models\Usuarios::USUARIO_DEVELOPER,
                    ],
                    [
                        'label' => 'Acerca Del Proyecto',
                        'icon' => 'info-circle',
                        'url' => ['site/about'],
                    ],
                ],   
            ]
        ) ?>

    </section>
</aside>
