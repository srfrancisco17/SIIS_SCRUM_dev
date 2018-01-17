<?php
/* @var $this yii\web\View */

use app\models\Usuarios;



?>
<?php
        
$script = <<< JS
$(document).ready(function(){
   var i = 1;
       
   $('#add').click(function(){
      i++; 
        
        $('#dynamic_field').append('<tr id="row'+i+'"><td><input type="text" name="name[]" id="name" placeholder="Enter Name" class="form-control name_list"/></td><td><input type="text" name="lugar[]" id="lugar" placeholder="Enter Lugar" class="form-control name_list"/></td><td><button name="remove" id="'+i+'" class = "btn btn-danger btn_remove">X</button></td></tr>');
        
   });
        
   $(document).on('click', '.btn_remove', function(){
    
        var button_id = $(this).attr("id");
        $("#row"+button_id+"").remove();
   
   });
   
   /*
   $('#submit').click(function(){
        $.ajax({
        
            url:"index.php?r=pruebas/insertar",
            method:"POST",
            data:$('#add_name').serialize(),
            succes: function(data)
            {
                alert(data);
                $('#add_name')[0].reset();
            }
   
        });
   });
   */     
});
JS;
  

$this->registerJs($script);
?>

<style>
    svg {
        display: block;
        font: 10.5em 'Roboto';
        font-family: 'Roboto', sans-serif;
        width: 240px;
        height: 75px;
        margin: 0 auto;
    }

    .web-coder-skull {
        fill: none;
        stroke: white;
        stroke-dasharray: 6% 29%;
        stroke-width: 5px;
        stroke-dashoffset: 0%;
        animation: stroke-offset 5.5s infinite linear;
    }

    .web-coder-skull:nth-child(6){
        stroke: #0677B1;
            animation-delay: -1;
    }

    .web-coder-skull:nth-child(2){
            stroke: #0677B1;
            animation-delay: -2s;
    }

    .web-coder-skull:nth-child(3){
            stroke: #0677B1;
            animation-delay: -3s;
    }

    .web-coder-skull:nth-child(4){
            stroke: #0677B1;
            animation-delay: -4s;
    }

    .web-coder-skull:nth-child(5){
            stroke: #0677B1;
            animation-delay: -5s;
    }
    .web-coder-skull:nth-child(1){ 
    stroke: #0677B1;
    animation-delay: -1s;
    }

    @keyframes stroke-offset{
            100% {stroke-dashoffset: -35%;}
    }
</style>
	<div class="row">
            <svg viewBox="0 0 860 250">
                <symbol id="web-coderskull">
                    <text text-anchor="middle" x="50%" y="70%">SIIS_SCRUM</text>
                </symbol>
            <g class = "webcoderskull">
                    <use xlink:href="#web-coderskull" class="web-coder-skull"></use>Raj Saini
                    <use xlink:href="#web-coderskull" class="web-coder-skull"></use>
                    <use xlink:href="#web-coderskull" class="web-coder-skull"></use>
                    <use xlink:href="#web-coderskull" class="web-coder-skull"></use>
                    <use xlink:href="#web-coderskull" class="web-coder-skull"></use>
            </g>
            </svg>
	</div>
