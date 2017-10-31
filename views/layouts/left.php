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
                <a href="#"><i class="fa fa-circle text-success"></i> En Linea</a>
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
                            //'visible' => Usuarios::USUARIO_SCRUM_MASTER,
                        ],
                        [
                            'label' => 'Gii',
                            'icon' => 'file-code-o',
                            'url' => ['/gii'],
                        ],
                        [
                            'label' => 'Comites', 
                            'icon' => 'book', 
                            'url' => ['comites/index'],
                        ],
                        [
                            'label' => 'Requerimientos',
                            'icon' => 'check-square-o',
                            'url' => ['requerimientos/index'],
                        ],
                        [
                            'label' => 'Sprints',
                            'icon' => 'undo',
                            'url' => ['sprints/index'],
                        ],
                        [
                            'label' => 'Diagramas',
                            'icon' => 'folder-open',
                            'visible' => FALSE,
                            'url' => ['#'],
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
                                ['label' => 'Tipos Usuarios', 'icon'=>'user-secret', 'url' => ['tipos-usuarios/index']],
                                ['label' => 'Tipos Documentos', 'icon'=>'folder', 'url' => ['tipos-documentos/index']],
                                ['label' => 'Departamentos', 'icon'=>'building', 'url' => ['departamentos/index']],
                            ],
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
                            //'visible' => Usuarios::USUARIO_DEVELOPER,
                        ],
                        [
                            'label' => 'Mis Sprints',
                            'icon' => 'undo',
                            'url' => ['sprint-usuarios/index'],
                        ],
                        [
                            'label' => 'Requerimientos',
                            'icon' => 'check-square-o',
                            'url' => ['requerimientos/index'],
                        ],
                        [
                            'label' => 'Acerca Del Proyecto',
                            'icon' => 'info-circle',
                            'url' => ['site/about'],
                        ],                    
                    ]
                ];                
                
                
            }else if (Yii::$app->user->identity->tipo_usuario == Usuarios::USUARIO_PRODUCT_OWNER){
                
                $menu = [
                    'options' => ['class' => 'sidebar-menu'],
                    'items' => [
                        ['label' => 'Menu PRODUCT_OWNER', 'options' => ['class' => 'header']],
                        [
                            'label' => 'Dashboard',
                            'icon' => 'dashboard ',
                            'url' => ['site/index-scrum-master'],

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
