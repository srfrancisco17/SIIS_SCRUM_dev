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


<div class="row">
    
    <div class="col-lg-12">
        <!--
        <div class="form-group">
            <form name="add_name" id="add_name">
                <table class="table table-bordered" id="dynamic_field">
                    <tr>
                        <td><input type="text" name="name[]" id="name" placeholder="Enter Name" class="form-control name_list"/></td>
                        <td><input type="text" name="lugar[]" id="lugar" placeholder="Enter Lugar" class="form-control name_list"/></td>
                        <td><button  type="button" name="add" id="add" class="btn-success">Add more</button></td>
                    </tr>
                </table>
                <input type="button" name="submit" id="submit" value="submit" />
            </form>
        </div>
        -->
        
        <div class="form-group">
            <form name="add_name" id="add_name" method="POST" action="index.php?r=pruebas/insertar">
                <table class="table table-bordered" id="dynamic_field">
                    <tr>
                        <td><input type="text" name="name[]" id="name" placeholder="Enter Name" class="form-control name_list"/></td>
                        <td><input type="text" name="lugar[]" id="lugar" placeholder="Enter Lugar" class="form-control name_list"/></td>
                        <td><button  type="button" name="add" id="add" class="btn-success">Add more</button></td>
                    </tr>
                </table>
                <input type="submit" name="submit" id="submit" value="submit" />
            </form>
        </div>
    </div>

</div>
