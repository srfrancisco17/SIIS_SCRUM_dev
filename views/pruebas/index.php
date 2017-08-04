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
    /*
.yii-debug-toolbar_position_bottom {
    position: fixed;
    right: 0;
    bottom: 0;
    margin: 0;
}

.yii-debug-toolbar {
    font: 11px Verdana, Arial, sans-serif;
    text-align: left;
    width: 96px;
    transition: width .3s ease;
    z-index: 1000000;
}   */
</style>

