<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Requerimientos Criterios';

$this->registerJs("
	$(document).ready(function(){
		$('[data-toggle=\"tooltip\"]').tooltip(); 
	});
");		

$ruta_save = Url::to(['requerimientos-criterios/save']);

?>
<style> 
    .panel-default > .panel-heading {
        color: #FFFFFF;
        background-color: #605ca8;
        border-color: #ddd;
    }
</style>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">Listado de requerimientos x criterios</div>
			<div class="panel-body">
			
				<?php Pjax::begin(['id' => 'requerimientos_criterios']); ?>
				
				
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th style="width: 1%; text-align: center;">#</th>
							<th style="width: 1%; text-align: center;">ID</th>
							<th style="width: 40%; text-align: left;">TITULO</th>
							<th style="width: 10%; text-align: center;">FECHA</th>
							<th style="width: 1%; text-align: center;">ESTADO</th>
							
							
							<?php
						
							
								foreach ($criterios as $indice => $criterio){
									
									echo "<th style='text-align: center;'><a href='#' data-toggle='tooltip' title='".$criterio['descripcion']."'>".$criterio['descripcion_abreviada']."(".$criterio['valor'].")</a></th>";
									
								}								
							
							?>
							<th style="width: 1%; text-align: center;">X</th>
							<th style="width: 5%;text-align: center;"><a href="#" data-toggle="tooltip" title="Peso ponderado">TOTAL</a></th>
						</tr>
					</thead>
					<tbody>

						<?php
						
							$i = 1;
							
							foreach ($requerimientos as $indice => $requerimiento){
								
								echo "<tr>";
								echo "	<td style='text-align: center;'>".$i."</td>";
								echo "	<td style='text-align: center;'>".$requerimiento['requerimiento_id']."</td>";
								echo "	<td>".$requerimiento['requerimiento_titulo']."</td>";
								echo "	<td style='text-align: center; '>".$requerimiento['fecha_requerimiento']."</td>";
								echo "	<td style='text-align: center; '>".$requerimiento['estado']."</td>";
								
								foreach($requerimiento['criterios'] as $criterio){
									
									echo "<td style='text-align: center; width: 5%;'>";
									echo "	<select onchange='calcular_valor_total(".$requerimiento['requerimiento_id'].");' class='requerimientos_criterios_".$requerimiento['requerimiento_id']."' id='".$requerimiento['requerimiento_id']."_".$criterio['criterio_id']."' name='".$requerimiento['requerimiento_id']."_".$criterio['criterio_id']."_".$criterio['criterio_valor']."'>";
									
		
									echo "		<option></option>";
									
									$option_cero = "";
									$option_uno = "";
									
									if ($criterio['requerimiento_valor'] == '0'){
										
										$option_cero = "selected='selected'";
										
									}else if ($criterio['requerimiento_valor'] == '1'){
										
										$option_uno = "selected='selected'";
									}
									
									
		
									echo "		<option ".$option_cero.">0</option>";
									echo "		<option ".$option_uno.">1</option>";
									
									echo "	</select>";
									echo "</td>";
									
									
								}
								
								echo "<td style='text-align: center; width: 1%;'>";
								echo "	<a id='link_' href='#' title='link' data-url='' data-pjax='0' onclick='save_requerimientos_criterios(".$requerimiento['requerimiento_id'].")'><span class='glyphicon glyphicon-ok'></span></a>";
								echo "</td>";
								
								echo "<td style='text-align: center; width: 1%;'>";
								echo "	<p id='valor_total_text_".$requerimiento['requerimiento_id']."'>".$requerimiento['valor_total']."</p>";
								echo "	<input type='hidden' value='".$requerimiento['valor_total']."' id='valor_total_".$requerimiento['requerimiento_id']."' name='valor_total_".$requerimiento['requerimiento_id']."'> ";
								echo "</td>";
								
								
								echo "</tr>";
								$i++;
							}
						
						?>
					</tbody>
				</table>
				
				<?php Pjax::end(); ?>
				
			</div>
		</div>
	</div>
</div>
<script>

	function save_requerimientos_criterios(requerimiento_id){
		
		var criterios = {};
		
		
		var valor_total = $("#valor_total_"+requerimiento_id).val();

		
		
		
		$(".requerimientos_criterios_"+requerimiento_id+"").each(function( index ) {
			
			select = $(this).attr('name').split('_');
	
			criterio_id = select[1];
			criterio_valor = $(this).val();
			
			
			if (criterio_id != ""){
				
				
				criterios["c"+criterio_id] = criterio_valor;

			}
			
		});
		
		if (confirm("Estas seguro de guardar los cambios?")) {
			

			$.ajax({
				data: { 
					requerimiento_id : requerimiento_id, 
					criterios : JSON.stringify(criterios),
					valor_total : valor_total
				},
				url:   "<?= $ruta_save ?>",
				type:  'POST',
				success:  function (response) {
					
					// console.log("success()");
					
				}
			}).done(function(data) {
				
					console.log("done()");
					$.pjax.reload({container: '#requerimientos_criterios'});
					
					
					
			});
		
		}

	}
	
	function calcular_valor_total(requerimiento_id){
	
		// valor_total_829
		
		var valor_total = 0;
		var flag = 0;

		$(".requerimientos_criterios_"+requerimiento_id+"").each(function( index ) {
			
			// $(this).attr('id')

			select_name = $(this).attr('name').split('_');
			
			var criterio_id = select_name[1];
			var criterio_valor = select_name[2];
			var value = $(this).val();
			
			
			// console.log(value);

			if (value != ''){
				
				if (value == '1'){
					
					valor_total += parseFloat(criterio_valor);
					
				}
				
				flag++;
				
			}
		
			
		});
		
		
		if (flag > 0){
			
			$("#valor_total_"+requerimiento_id).val(valor_total.toFixed(1));
		
			$('#valor_total_text_'+requerimiento_id).text(valor_total.toFixed(1));
			
		}else{
			
			$("#valor_total_"+requerimiento_id).val('');
			
			$('#valor_total_text_'+requerimiento_id).text('');
			
		}
	}
</script>