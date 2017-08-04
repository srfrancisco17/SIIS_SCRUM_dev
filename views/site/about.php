<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-6">

        <ul class="timeline">
            <!-- timeline time label -->
            <li class="time-label">
                <span class="bg-yellow">
                    Informacion general
                </span>
            </li>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <li>
                <i class="fa fa-code bg-aqua"></i>
                <div class="timeline-item">
                    <span class="time"></span>
                    <h3 class="timeline-header" style="background: #f9f9f9">Análisis, Diseño y Desarrollo de software</h3>
                    <div class="timeline-body">
                        Francisco Andrés Ortega Florez
                    </div>
                </div>
            </li>
            <li>
                <i class="fa fa-user bg-aqua"></i>
                <div class="timeline-item">
                    <span class="time"></span>
                    <h3 class="timeline-header" style="background: #f9f9f9">Análisis - Desarrollo de software - Administración de la plataforma</h3>
                    <div class="timeline-body">
                        Ing. Diego Luis Naranjo Cardona
                    </div>
                </div>
            </li>
            <!-- END timeline item -->
            <!-- timeline item -->
            <li>
                <i class="fa fa-lightbulb-o bg-aqua"></i>
                <div class="timeline-item">
                    <span class="time"></span>
                    <h3 class="timeline-header" style="background: #f9f9f9">Idea y Supervisión</h3>
                    <div class="timeline-body">
                        Ing. Diego Luis Naranjo Cardona
                    </div>
                </div>
            </li>
            <!-- END timeline item -->
            <!-- timeline item -->
            <li>
                <i class="fa fa-group bg-aqua"></i>
                <div class="timeline-item">
                    <span class="time"></span>
                    <h3 class="timeline-header" style="background: #f9f9f9">Agradecimientos</h3>
                    <div class="timeline-body">
                        - Jorge Alberto Rodríguez (Desarrollador)<br>
                        - Juan Pablo Sánchez (Desarrollador)<br>
                        - Harrison Gonzales (Desarrollador)<br>
                        - Leonardo Rodríguez (Desarrollador)<br>
                        - Luisa Fernanda Bedoya (Desarrollador)<br>
                        - Juan Fernando Hoyos (Desarrollador)<br>
                    </div>
                </div>
            </li>
            <li>
                <i class="fa fa-end"></i>
            </li>
        </ul>
    </div>
    <div class="col-lg-6">
        <?= Html::img('@web/img/desarrolladores.png', ['alt' => 'My logo', 'style' => 'margin-top: 60px']) ?>
    </div>
</div>


