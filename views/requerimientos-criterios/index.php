<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

use kartik\select2\Select2;

$this->title = 'Requerimientos Criterios';


$ruta_save = Url::to(['requerimientos-criterios/save']);

$this->registerCssFile(Yii::getAlias("@web")."/css/datatables.min.css");
$this->registerJsFile(Yii::getAlias("@web")."/js/datatables.min.js", ["depends" => [\yii\web\JqueryAsset::className()]]);

$this->registerJs("
	$(function(){
		$('body').addClass('sidebar-collapse');
		
	});
");	
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
			<div class="panel-heading">LISTADO DE REQUERIMIENTOS X CRITERIOS</div>
			<div class="panel-body">
			
			<div class="row">
				<div class="col-lg-5">
					<?php
					echo '<label class="control-label">Estados</label>';
					echo Select2::widget([
						'id' => 'mySelect',
						'name' => 'mySelect',
						'data' => $estados_new,
						'options' => [
							'placeholder' => 'Seleccione',
							'multiple' => true
						],
					]);
					?>
				</div>
			</div>

				<?php 
				
					Pjax::begin(['id' => 'requerimientos_criterios']); 
					
					$this->registerJs("

						$( function(){
							
							$('[data-toggle=\"tooltip\"]').tooltip();
							
							var table = $('#table_requerimientos_criterios').DataTable( {
								// fixedHeader: true,
								bSort: false,
								// bPaginate: false,
								bInfo: true,
								deferRender:    true,
								scrollY:        400,
								scrollX:        true,
								scrollCollapse: true,
								scroller:       true,
								'language': {
									'url': '//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json'
								}
							} );
							
						} );
				
				
						$('#mySelect').on('change',function(){
			
							filtroEstado();

						});
						
					");
					
				?>

				<div class="table-responsive">
					<table id='table_requerimientos_criterios' class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th style="width: 1%; text-align: center;">#</th>
								<th style="width: 1%; text-align: center;">ID</th>
								<th style="width: 20%; text-align: left;">TITULO</th>
								<th style="width: 10%; text-align: center;">FECHA</th>
								<th style="width: 1%; text-align: center;">ESTADO</th>
								
								<?php
							
									foreach ($criterios as $indice => $criterio){
										
										echo "<th style='text-align: center;'><a href='#' data-toggle='tooltip' data-container='body' data-placement='bottom' title='".$criterio['descripcion']."'>".$criterio['descripcion_abreviada']."(".$criterio['valor'].")</a></th>";
										
									}								
								
								?>
								<th style="width: 1%; text-align: center;">X</th>
								<th style="width: 5%;text-align: center;"><a href="#" data-toggle="tooltip" data-placement="bottom" title="Peso ponderado">TOTAL</a></th>
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
									echo "	<td style='text-align: center; ' data-column='".$requerimiento['estado_id']."'>".$requerimiento['estado_id']."-".$requerimiento['estado']."</td>";
									
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
				</div>
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
				success:  function (response) {}
			}).done(function(data) {
				
					// console.log("done()");
					$.pjax.reload({container: '#requerimientos_criterios'});
					filtroEstado();
					
			});
		
		}

	}
	
	function calcular_valor_total(requerimiento_id){
		
		var valor_total = 0;
		var flag = 0;

		$(".requerimientos_criterios_"+requerimiento_id+"").each(function( index ) {

			select_name = $(this).attr('name').split('_');
			
			var criterio_id = select_name[1];
			var criterio_valor = select_name[2];
			var value = $(this).val();
		
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
	
	function filtroEstado(){
		

		console.log($('#mySelect').val());
		
		var search = [];
	  
		$.each($('#mySelect option:selected'), function(){
			search.push($(this).val());
		});
	  
		
		search = search.join('|');
		
		setTimeout(function(){ 
			$('#table_requerimientos_criterios').DataTable().column(4).search(search, true, false).draw();  
		}, 1000);

		
	}
</script>