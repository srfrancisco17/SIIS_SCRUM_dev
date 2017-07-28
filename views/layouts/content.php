<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;
?>
<div class="content-wrapper">
    <section class="content-header">
        <?php 
            if (isset($this->blocks['content-header'])) { 
            
        ?>
        <h1><?= $this->blocks['content-header'] ?></h1>
        <?php 
        
            } else { ?>
            <h1>
                <?php
                    if ($this->title !== null) {
                        //echo \yii\helpers\Html::encode($this->title);
                        echo '<br>';
                    } else {
                        echo \yii\helpers\Inflector::camel2words(\yii\helpers\Inflector::id2camel($this->context->module->id));
                        echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
                    }   
                ?>
            </h1>
        <?php } ?>
        <?=
        Breadcrumbs::widget(
            [
                'homeLink' => ['label' => 'Inicio',  'url' =>  (Yii::$app->user->identity->tipo_usuario == \app\models\Usuarios::USUARIO_SCRUM_MASTER) ? ['site/index-scrum-master'] : ['site/index-developer'] ],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
    </section>
    <section class="content">
        <?= Alert::widget() ?>
        <?= $content ?>
    </section>
</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 2.0.55
    </div>
    <strong>Copyright &copy; 2017-2018 <a href="http://www.clinicadeoccidente.com.co/">Clinicia Occidente(SIIS)</a>.</strong> Todos los derechos reservados.
</footer>
<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
        <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
        <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <!-- Home tab content -->
        <div class="tab-pane" id="control-sidebar-home-tab">
            <h3 class="control-sidebar-heading">Recent Activity</h3>
            <ul class='control-sidebar-menu'>
                <li>
                    <a href='javascript::;'>
                        <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                            <p>Will be 23 on April 24th</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href='javascript::;'>
                        <i class="menu-icon fa fa-user bg-yellow"></i>

                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                            <p>New phone +1(800)555-1234</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href='javascript::;'>
                        <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                            <p>nora@example.com</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href='javascript::;'>
                        <i class="menu-icon fa fa-file-code-o bg-green"></i>

                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                            <p>Execution time 5 seconds</p>
                        </div>
                    </a>
                </li>
            </ul>
            <!-- /.control-sidebar-menu -->

            <h3 class="control-sidebar-heading">Tasks Progress</h3>
            <ul class='control-sidebar-menu'>
                <li>
                    <a href='javascript::;'>
                        <h4 class="control-sidebar-subheading">
                            Custom Template Design
                            <span class="label label-danger pull-right">70%</span>
                        </h4>

                        <div class="progress progress-xxs">
                            <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href='javascript::;'>
                        <h4 class="control-sidebar-subheading">
                            Update Resume
                            <span class="label label-success pull-right">95%</span>
                        </h4>

                        <div class="progress progress-xxs">
                            <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href='javascript::;'>
                        <h4 class="control-sidebar-subheading">
                            Laravel Integration
                            <span class="label label-waring pull-right">50%</span>
                        </h4>

                        <div class="progress progress-xxs">
                            <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href='javascript::;'>
                        <h4 class="control-sidebar-subheading">
                            Back End Framework
                            <span class="label label-primary pull-right">68%</span>
                        </h4>

                        <div class="progress progress-xxs">
                            <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                        </div>
                    </a>
                </li>
            </ul>
            <!-- /.control-sidebar-menu -->
        </div>
        <!-- /.tab-pane -->
        <!-- Settings tab content -->
        <div class="tab-pane" id="control-sidebar-settings-tab">
            <div class="row">
                <div class="col-lg-12" style="text-align: center;">
                    <h3 class="control-sidebar-heading">Configuraciones</h3>
                </div>
                <div class="col-lg-12">
                    <div class="box box-solid">
                       <div class="box-body no-padding">
                           <table id="layout-skins-list" class="table table-striped bring-up nth-2-center">
                               <thead>
                               <tr>
                                   <th style="width: 210px; text-align: center;">Skin Class</th>
                               </tr>
                               </thead>
                               <tbody>
                               <tr>
                                   <td>
                                       <?= Html::a('<i class="fa fa-eye"></i>', 'javascript:void(0)', ['class' => 'btn btn-primary', 'onclick' => 'change_skin("skin-blue")', 'style' => 'width: 180px;']) ?>
                                       <!--<a style="width: 180px;" href="#" data-skin="skin-blue" class="btn btn-primary"><i class="fa fa-eye"></i></a>-->
                                   </td>
                               </tr>
                               <tr>
                                   <td>
                                       <?= Html::a('<i class="fa fa-eye"></i>', 'javascript:void(0)', ['class' => 'btn btn-warning', 'onclick' => 'change_skin("skin-yellow")', 'style' => 'width: 180px;'])?>
                                       <!--<a style="width: 180px;" href="#" data-skin="skin-yellow-light" class="btn btn-warning"><i class="fa fa-eye"></i></a>-->
                                   </td>
                               </tr>
                               <tr>
                                    <td>
                                        <?= Html::a('<i class="fa fa-eye"></i>', 'javascript:void(0)', ['class' => 'btn btn-success', 'onclick' => 'change_skin("skin-green")', 'style' => 'width: 180px;'])?>
                                    </td>  
                               </tr>
                               <tr>
                                   <td>
                                       <?= Html::a('<i class="fa fa-eye"></i>', 'javascript:void(0)', ['class' => 'btn bg-purple', 'onclick' => 'change_skin("skin-purple")', 'style' => 'width: 180px;'])?>
                                       <!--<a style="width: 180px;" href="#" data-skin="skin-purple" class="btn bg-purple"><i class="fa fa-eye"></i></a>-->
                                   </td>
                               </tr>
                               <tr>
                                   <td>
                                       <?= Html::a('<i class="fa fa-eye"></i>', 'javascript:void(0)', ['class' => 'btn btn-danger', 'onclick' => 'change_skin("skin-red")', 'style' => 'width: 180px;'])?>
                                       <!--<a style="width: 180px;" href="#" data-skin="skin-red" class="btn btn-danger"><i class="fa fa-eye"></i></a>-->
                                   </td>
                               </tr>
                               <tr>
                                   <td>
                                       <?= Html::a('<i class="fa fa-eye"></i>', 'javascript:void(0)', ['class' => 'btn bg-black', 'onclick' => 'change_skin("skin-black")', 'style' => 'width: 180px;'])?>
                                       <!--<a style="width: 180px;" href="#" data-skin="skin-black" class="btn bg-black"><i class="fa fa-eye"></i></a>-->
                                   </td>
                               </tr>
                               </tbody>
                           </table>
                       </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.tab-pane -->
    </div>
</aside><!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class='control-sidebar-bg'></div>
<?php
$this->registerJs("
    $(function (){
            
        change_skin = function(parametro){
       
            localStorage.setItem('skin', parametro);
            $('body').attr('class', parametro+' '+'sidebar-mini');


            $.ajax({
                    type: 'POST',    
                    url:'index.php?r=usuarios/respuesta&id='+parametro,
            }); 
        }
        
    });
");
?>
