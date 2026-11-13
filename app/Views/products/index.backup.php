
<!-- Contact Section-->
<section class="page-section" id="contact">
    <div class="container">
        <!-- Contact Section Heading-->
        <h4 class="page-section-heading text-center text-uppercase text-secondary mb-0">Dashboard</h4>
        <!-- Icon Divider-->
        <div class="divider-custom">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
            <div class="divider-custom-line"></div>
        </div>
        <?php if($this->session->flashdata('message')) { ?>
            <div class="alert alert-success">
                <?php echo $this->session->flashdata('message')?>
            </div>
        <?php } ?>
        <?php if($this->session->flashdata('e_message')) { ?>
            <div class="alert alert-danger">
                <?php echo $this->session->flashdata('e_message')?>
            </div>
        <?php } ?>
        <!-- Contact Section Form-->
        <div class="clearfix well">
			<!--<a class="btn btn-info pull-right" href="<?php echo site_url('products/add'); ?>">Add New Record</a>-->
			<a class="btn btn-info pull-right" href="<?php echo site_url('products/list'); ?>">Product List</a>
			
		</div>
        <?php if(!empty($mapping_data)) {?>
	        <div id="mydiv">
		        <div class="table-responsive">
			        <table class="table table-bordered table-hover table-condensed" id="tblLocations">
			        	<thead class="thead-light">
				        	<tr>
				        		<th>Reorder</th>
				        		<th>Product ID</th>
				        		<th>Image</th>
				        		<th>Product SKU</th>
					        	<th>Name</th>
				        	</tr>
			        	</thead>
			        	<tbody id="tble_sort">
			        	<?php
			        	$i = 1;
			        	foreach ($mapping_data as $key => $value) { 
			        		?>
			        		<tr id="<?php echo $value->id;?>">
			        			<td><?php echo $value->sequence_order; ?></td>
			        			<td><?php echo $value->id;?></td>
				     			<td>
					        		<img weight="100" height="100" src="<?php echo $value->image?>"/>
					        	</td>
					        	<td><?php echo $value->sku; ?></td>
					        	<td class="editableColumns"><?php echo $value->name; ?></td>
			        		</tr>
			        	<?php
			        		$i++;
			        	 } ?>
			        	</tbody>
			        </table>
		    	</div>
	    	</div>
    	<?php } ?>
    </div>
</section>
<script type="text/javascript">	
$(function () { 
	$("#tble_sort").sortable({
        //items: 'tr:not(tr:first-child)',
        cursor: 'pointer',
        axis: 'y',
        dropOnEmpty: false,
        start: function (e, ui) {
            ui.item.addClass("selected");
        },
        update: function (e, ui) {
        	var selectedData = new Array();
            $('#tble_sort>tr').each(function() {
                selectedData.push($(this).attr("id"));
            });
            ui.item.removeClass("selected");

            /*
            ui.item.removeClass("selected");
            $(this).find("tr").each(function (index) {
                if (index > 0) {
                    $(this).find("td").eq(0).html(index);
                }
            });*/
            $('#tble_sort>tr').each(function(index) {
            	$(this).children('td:first-child').html($(this).index()+1);
	        });
            updateOrder(selectedData);
            $("#tble_sort").sortable('refresh');
            $("#tble_sort").sortable( "refreshPositions" );     
        }
    });
});

function updateOrder(data) {
    $.ajax({
        url:'<?php echo base_url(); ?>products/update_squence_order',
        type:'POST',
        data:{position:data},
        success:function(){
            alert('Product order has been changed successfully');
            //$("#mydiv").load(location.href + " #mydiv");  
        }
    })
}
function remove_data(product_id) {
		if (confirm('Are you sure ?')) {
			$.ajax({
	           url: '<?php echo base_url(); ?>products/remove',
	           type: 'POST',
	           data: {id: product_id},
	           error: function() {
	              alert('Something is wrong');
	           },
	           success: function(data) {
	           		//console.log(data);
	           		alert("Record Removed Successfully!");
	                $("#mydiv").load(location.href + " #mydiv");  
	           }
	        });
		}
	}
</script>