<?php
//use Yii;
use yii\helpers\Html;
use app\models\Usuarios;

?>
<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <?= Html::img('@web/img/icono-cdo.png', ['alt' => 'My logo']) ?>
            </div>
            <div class="pull-left info">
                <p>
                    <?= Yii::$app->user->identity->nombres ?>
                </p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Buscar..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <?php
            
            $menu = array();
            
            
            if (Yii::$app->user->identity->tipo_usuario == Usuarios::USUARIO_SCRUM_MASTER){
                
                $menu = [
                    'options' => ['class' => 'sidebar-menu'],
                    'items' => [
                        ['label' => 'Menu Scrum-Master', 'options' => ['class' => 'header']],
                        [
                            'label' => 'Dashboard',
                            'icon' => 'dashboard ',
                            'url' => ['site/index-scrum-master'],
                            'visible' => Usuarios::USUARIO_SCRUM_MASTER,
                        ],
                        [
                            'label' => 'Gii',
                            'icon' => 'file-code-o',
                            'url' => ['/gii'],
                            'visible' => Usuarios::USUARIO_SCRUM_MASTER,
                        ],
                        [
                            'label' => 'Comites', 
                            'icon' => 'book', 
                            'url' => ['comites/index'],
                            'visible' => Usuarios::USUARIO_SCRUM_MASTER,
                        ],
                        [
                            'label' => 'Requerimientos',
                            'icon' => 'check-square-o',
                            'url' => ['requerimientos/index'],
                            'visible' => Usuarios::USUARIO_SCRUM_MASTER ||\app\models\Usuarios::USUARIO_DEVELOPER,
                        ],
                        [
                            'label' => 'Sprints',
                            'icon' => 'undo',
                            'url' => ['sprints/index'],
                            'visible' => Usuarios::USUARIO_SCRUM_MASTER,
                        ],
                        [
                            'label' => 'Diagramas',
                            'icon' => 'folder-open',
                            'url' => ['#'],
                            'visible' => Usuarios::USUARIO_SCRUM_MASTER,
                            'items' => [
                                ['label' => 'Gantt', 'icon'=>'align-left', 'url' => ['diagramas/gantt']],
                                ['label' => 'area-chart', 'icon'=>'area-chart', 'url' => ['#']],
                                ['label' => 'bar-chart', 'icon'=>'bar-chart', 'url' => ['#']],
                            ],
                        ],
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
                            'visible' => Usuarios::USUARIO_SCRUM_MASTER,
                        ],
                        [
                            'label' => 'Acerca Del Proyecto',
                            'icon' => 'info-circle',
                            'url' => ['site/about'],
                        ],
                    ]
                ];
                
            }else if (Yii::$app->user->identity->tipo_usuario == Usuarios::USUARIO_DEVELOPER){
                
                $menu = [
                    'options' => ['class' => 'sidebar-menu'],
                    'items' => [
                        ['label' => 'Menu Desarrollador', 'options' => ['class' => 'header']],
                        [
                            'label' => 'Dashboard',
                            'icon' => 'dashboard ',
                            'url' => ['site/index-developer'],
                            'visible' => Usuarios::USUARIO_SCRUM_MASTER,
                        ],
                        [
                            'label' => 'Mis Sprints',
                            'icon' => 'undo',
                            'url' => ['sprint-usuarios/index'],
                            'visible' => Usuarios::USUARIO_DEVELOPER,
                        ],
                        [
                            'label' => 'Acerca Del Proyecto',
                            'icon' => 'info-circle',
                            'url' => ['site/about'],
                        ],                    
                    ]
                ];                
                
                
            }
 
        ?>
         
        <?= dmstr\widgets\Menu::widget($menu) ?>

    </section>
</aside>
