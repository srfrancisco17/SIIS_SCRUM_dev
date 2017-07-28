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
 <div class="box box-solid" style="max-width: 150px;">
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
                            <a style="width: 130px;" href="#" data-skin="skin-blue" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a style="width: 130px;" href="#" data-skin="skin-yellow-light" class="btn btn-warning btn-xs"><i class="fa fa-eye"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td><a style="width: 130px;" href="#" data-skin="skin-green" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a style="width: 130px;" href="#" data-skin="skin-purple" class="btn bg-purple btn-xs"><i class="fa fa-eye"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td><a style="width: 130px;" href="#" data-skin="skin-red" class="btn btn-danger btn-xs"><i class="fa fa-eye"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td><a style="width: 130px;" href="#" data-skin="skin-black" class="btn bg-black btn-xs"><i class="fa fa-eye"></i></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>   
   
</div>
