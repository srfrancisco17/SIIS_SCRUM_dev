<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">
   
    <?php
        if(Yii::$app->user->identity->tipo_usuario == 1){
            
            echo Html::a('<span class="logo-mini">CDO</span><span class="logo-lg">' . Yii::$app->name . '</span>', Url::to(['site/index-scrum-master']), ['class' => 'logo']);
        
            
        }else if(Yii::$app->user->identity->tipo_usuario == 2){
            
            echo Html::a('<span class="logo-mini">CDO</span><span class="logo-lg">' . Yii::$app->name . '</span>', Url::to(['site/index-developer']), ['class' => 'logo']);
        
            
        }else{
            echo Html::a('<span class="logo-mini">CDO</span><span class="logo-lg">' . Yii::$app->name . '</span>', '#', ['class' => 'logo']);
        }
        
    ?>
  
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav"> 
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!--<img src="<?= $directoryAsset ?>/img/icono-cdo.png" class="user-image" alt="User Image"/>-->
                        
                        <?= Html::img('@web/img/icono-cdo.png', ['class' => 'user-image', 'alt' => 'User Image']) ?>
                        <!--<span class="hidden-xs">Francisco Ortega</span>-->
                        <span class="hidden-xs">
                            <?php
                                if (Yii::$app->user->isGuest) {
                                    echo 'Soy Desconocido';
                                }else{
                                    echo Yii::$app->user->identity->nombres.' '.Yii::$app->user->identity->apellidos;
                                }
                            
                            ?> 
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <?= Html::img('@web/img/icono-cdo.png', ['class' => 'img-circle', 'alt' => 'User Image']) ?>
                            <p>
                                <?php
                                    echo Yii::$app->user->identity->nombreCompleto;
                                    echo '<small>'.Yii::$app->user->identity->tipoUsuario->descripcion.' - '.Yii::$app->user->identity->departamento0->descripcion.'</small>';  
                                ?>
                                <!--<small>Miembro desde Feb. 2017</small>-->

                            </p>
                        </li>
                        <!-- Menu Body 
                        <li class="user-body">
                            <div class="col-xs-4 text-center">
                                <a href="#">Followers</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Sales</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Friends</a>
                            </div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <!--<a href="#" class="btn btn-default btn-flat">Perfil</a>-->
                                <?= Html::a('Perfil', ['/usuarios/profile'], ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Salir',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-danger btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- User Account: style can be found in dropdown.less -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
