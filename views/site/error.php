<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<section class="content">

    <div class="error-page">
        <h2 class="headline text-info"><i class="fa fa-warning text-yellow"></i></h2>

        <div class="error-content">
            <h3><?= $name ?></h3>
            <br>
            <h4>
                <?= nl2br(Html::encode($message)) ?>
            </h4>
            <br>
            <p>
                El error anterior se produjo mientras el servidor Web estaba procesando su solicitud.
                Póngase en contacto con nosotros si cree que se trata de un error de servidor.
                Gracias. Mientras tanto, puede <a href='<?= Yii::$app->homeUrl ?>'>Regresar Al Dashboard</a>.
            </p>
            <!--
            <form class='search-form'>
                <div class='input-group'>
                    <input type="text" name="search" class='form-control' placeholder="Search"/>

                    <div class="input-group-btn">
                        <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            -->
        </div>
    </div>

</section>
