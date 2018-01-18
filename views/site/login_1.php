<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */
$this->title = 'Iniciar sesión';
$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-user form-control-feedback'></span>"
];
$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];

$this->registerCssFile("@web/css/login.css");
$this->registerCss("
    
    @import url('https://fonts.googleapis.com/css?family=Nunito');
    @import url('https://fonts.googleapis.com/css?family=Poiret+One');
    
    body, html {
            height: 100%;
            background-repeat: no-repeat;    /*background-image: linear-gradient(rgb(12, 97, 33),rgb(104, 145, 162));*/
            background:black;
            position: relative;
    }
    #login-box {
            position: absolute;
            top: 120px;
            left: 50%;
            transform: translateX(-50%);
            width: 350px;
            margin-top: 0px;
            /*margin: 0 auto;*/
            border: 1px solid black;
            background-color: #f2f2f2;
            min-height: 250px;
            padding: 20px;
            z-index: 9999;

    }
    #login-box .logo .logo-caption {
            font-family: 'Poiret One', cursive;
            color: white;
            text-align: center;
            margin-bottom: 0px;
    }
    #login-box .logo .tweak {
            color: #ff5252;
    }
    .login-logo a, .register-logo a{
        color: #565656;
    }
    #login-box .controls {
            padding-top: 0px;
    }
    #login-box .controls input {
            border-radius: 0px;
            background: rgb(98, 96, 96);
            border: 0px;
            color: white;
            font-family: 'Nunito', sans-serif;
    }
    #login-box .controls input:focus {
            box-shadow: none;
    }
    #login-box .controls input:first-child {
            border-top-left-radius: 2px;
            border-top-right-radius: 2px;
    }
    #login-box .controls input:last-child {
            border-bottom-left-radius: 2px;
            border-bottom-right-radius: 2px;
    }
    #login-box button.btn-custom {
            border-radius: 2px;
            margin-top: 8px;
            background:#ff5252;
            border-color: rgba(48, 46, 45, 1);
            color: white;
            font-family: 'Nunito', sans-serif;
    }
    #login-box button.btn-custom:hover{
            -webkit-transition: all 500ms ease;
            -moz-transition: all 500ms ease;
            -ms-transition: all 500ms ease;
            -o-transition: all 500ms ease;
            transition: all 500ms ease;
            background: rgba(48, 46, 45, 1);
            border-color: #ff5252;
    }
    #particles-js{
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: 50% 50%;
            position: fixed;
            background-color: #1D1F20;
            top: 0px;
            z-index:1;
    }    
");


$this->registerJs('
//<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>-->
    $.getScript("'.Yii::getAlias('@web').'/js/particles.min.js", function(){
        particlesJS("particles-js",
          {
            "particles": {
              "number": {
                "value": 50,
                "density": {
                  "enable": true,
                  "value_area": 800
                }
              },
              "color": {
                "value": "#ffffff"
              },
              "shape": {
                "type": "circle",
                "stroke": {
                  "width": 0,
                  "color": "#000000"
                },
                "polygon": {
                  "nb_sides": 5
                },
                "image": {
                  "width": 100,
                  "height": 100
                }
              },
              "opacity": {
                "value": 0.5,
                "random": false,
                "anim": {
                  "enable": false,
                  "speed": 1,
                  "opacity_min": 0.1,
                  "sync": false
                }
              },
              "size": {
                "value": 5,
                "random": true,
                "anim": {
                  "enable": false,
                  "speed": 40,
                  "size_min": 0.1,
                  "sync": false
                }
              },
              "line_linked": {
                "enable": true,
                "distance": 150,
                "color": "#ffffff",
                "opacity": 0.4,
                "width": 1
              },
              "move": {
                "enable": true,
                "speed": 6,
                "direction": "none",
                "random": false,
                "straight": false,
                "out_mode": "out",
                "attract": {
                  "enable": false,
                  "rotateX": 600,
                  "rotateY": 1200
                }
              }
            },
            "interactivity": {
              "detect_on": "canvas",
              "events": {
                "onhover": {
                  "enable": true,
                  "mode": "repulse"
                },
                "onclick": {
                  "enable": true,
                  "mode": "push"
                },
                "resize": true
              },
              "modes": {
                "grab": {
                  "distance": 400,
                  "line_linked": {
                    "opacity": 1
                  }
                },
                "bubble": {
                  "distance": 400,
                  "size": 40,
                  "duration": 2,
                  "opacity": 8,
                  "speed": 3
                },
                "repulse": {
                  "distance": 200
                },
                "push": {
                  "particles_nb": 4
                },
                "remove": {
                  "particles_nb": 2
                }
              }
            },
            "retina_detect": true,
            "config_demo": {
              "hide_card": false,
              "background_color": "#b61924",
              "background_image": "",
              "background_position": "50% 50%",
              "background_repeat": "no-repeat",
              "background_size": "cover"
            }
          }
        );
    });
');

/*
$this->registerJs('
//<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>-->
    $.getScript("'.Yii::getAlias('@web').'/js/particles.min.js", function(){
        particlesJS("particles-js",
{
  "particles": {
    "number": {
      "value": 150,
      "density": {
        "enable": true,
        "value_area": 800
      }
    },
    "color": {
      "value": "#fff"
    },
    "shape": {
      "type": "circle",
      "stroke": {
        "width": 0,
        "color": "#000000"
      },
      "polygon": {
        "nb_sides": 5
      },
      "image": {
        "src": "img/github.svg",
        "width": 100,
        "height": 100
      }
    },
    "opacity": {
      "value": 0.5,
      "random": true,
      "anim": {
        "enable": false,
        "speed": 1,
        "opacity_min": 0.1,
        "sync": false
      }
    },
    "size": {
      "value": 10,
      "random": true,
      "anim": {
        "enable": false,
        "speed": 40,
        "size_min": 0.1,
        "sync": false
      }
    },
    "line_linked": {
      "enable": false,
      "distance": 500,
      "color": "#ffffff",
      "opacity": 0.4,
      "width": 2
    },
    "move": {
      "enable": true,
      "speed": 6,
      "direction": "bottom",
      "random": false,
      "straight": false,
      "out_mode": "out",
      "bounce": false,
      "attract": {
        "enable": false,
        "rotateX": 600,
        "rotateY": 1200
      }
    }
  },
  "interactivity": {
    "detect_on": "canvas",
    "events": {
      "onhover": {
        "enable": true,
        "mode": "bubble"
      },
      "onclick": {
        "enable": true,
        "mode": "repulse"
      },
      "resize": true
    },
    "modes": {
      "grab": {
        "distance": 400,
        "line_linked": {
          "opacity": 0.5
        }
      },
      "bubble": {
        "distance": 400,
        "size": 4,
        "duration": 0.3,
        "opacity": 1,
        "speed": 3
      },
      "repulse": {
        "distance": 200,
        "duration": 0.4
      },
      "push": {
        "particles_nb": 4
      },
      "remove": {
        "particles_nb": 2
      }
    }
  },
  "retina_detect": true
}
        );
    });
');
*/
?>
<div class="container">
	<div id="login-box">
            <div class="login-logo"> 
                
                <?= Html::img('@web/img/icono-cdo.png', ['alt' => 'My logo', 'style' => ['width' => '150px', 'height' => '150px']]) ?>
                <br>
                <a href="#"><b>SIIS</b>-SCRUM</a>
            </div>
            <div class="controls">
                <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>
                    <?= $form
                        ->field($model, 'username', $fieldOptions1)
                        ->label(false)
                        ->textInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('Numero de documento')]) ?>

                    <?= $form
                        ->field($model, 'password', $fieldOptions2)
                        ->label(false)
                        ->passwordInput(['placeholder' => 'Contraseña']) ?>

                    <?= Html::submitButton('<i class="glyphicon glyphicon-log-in"></i> Iniciar sesion', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>    
                <?php ActiveForm::end(); ?>
            </div>
	</div>
</div>
<div id="particles-js"></div>
<!--<script type="text/javascript" src="http://blogparts.giffy.me/0017/parts.js"></script>-->
<!-- Santa Claus volando por el blog -->
<!--<script src="/SIIS_SCRUM_dev/web/js/parts.js"></script>-->
