<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;


$this->title = 'PRUEBAS';

?>
<style>
    
.nav-tabs { border-bottom: 2px solid #DDD; }
.nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover { border-width: 0; }
.nav-tabs > li > a { border: none; color: #666; }
.nav-tabs > li.active > a, .nav-tabs > li > a:hover { border: none; color: #4285F4 !important; background: transparent; }
.nav-tabs > li > a::after { content: ""; background: #4285F4; height: 2px; position: absolute; width: 100%; left: 0px; bottom: -1px; transition: all 250ms ease 0s; transform: scale(0); }
.nav-tabs > li.active > a::after, .nav-tabs > li:hover > a::after { transform: scale(1); }
.tab-nav > li > a::after { background: #21527d none repeat scroll 0% 0%; color: #fff; }
.tab-pane { padding: 15px 0; }
.tab-content{padding:20px}
.card {background: #FFF none repeat scroll 0% 0%; box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.3); margin-bottom: 30px; }

    
</style>

<div class="row">
    <div class="col-md-12">
        <!-- Nav tabs -->
        <div class="card">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">DETALLE HISTORIA DE USUARIO</a></li>
                <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">PRUEBAS</a></li>
                <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">IMPLEMENTACION</a></li>

            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="home">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lm Ipsum.</div>
                <div role="tabpanel" class="tab-pane" id="profile">pecimen book.</div>
                <div role="tabpanel" class="tab-pane" id="messages">PageMaker including versions of Lorem Ipsum.</div>
            </div>
        </div>
    </div>
</div>
