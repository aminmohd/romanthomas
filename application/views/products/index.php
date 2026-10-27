<div class="overlay"></div>
<!-- Section -->
<section class="page-section" id="contact">
    <div class="container">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-default">
            <div class="collapse navbar-collapse" id="main_nav">
                 <ul class="navbar-nav">
                    <?php 
                        if(!empty($categories)) {
                            foreach ($categories as $ck=>$cval) { 
                                    $class_dropdown =  (!empty($cval['childs'])) ? 'dropdown' : '';
                                     $class_dropdown_toggle =  (!empty($cval['childs'])) ? 'dropdown-toggle' : '';
                                ?>

                                <li class="nav-item <?php echo $class_dropdown ?>">
                                    <a class="nav-link <?php echo $class_dropdown_toggle; ?>" href="<?php echo site_url("products/index/".$cval['id']); ?>" data-toggle="<?php echo $class_dropdown ?>">
                                        <?php 
                                            if($cval['name']=='Inventory') 
                                                { echo 'the SHOP';} 
                                            else 
                                                { echo $cval['name']; } 
                                        ?> 
                                    </a>
                                    <?php if(!empty($cval['childs'])) {?>
                                        <ul class="dropdown-menu">
                                            <?php foreach($cval['childs'] as $cc=>$child) { 
                                                    $class_dropdown_toggle =  (!empty($child['subchilds'])) ? 'dropdown-toggle' : '';
                                                ?>
                                                <li><a class="dropdown-item <?php echo $class_dropdown_toggle ?>" href="<?php echo site_url("products/index/".$child['id']); ?>"> <?php echo strtoupper($child['name']);?> </a>
                                                    <?php if((!empty($child['subchilds']))) { ?>
                                                        <ul class="submenu dropdown-menu">
                                                             <?php foreach($child['subchilds'] as $sk=>$sval) {?>
                                                            <li><a class="dropdown-item" href="<?php echo site_url("products/index/".$sval['id']); ?>"><?php echo $sval['name']?></a></li>
                                                            <?php } ?>
                                                        </ul>
                                                    <?php } ?>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    <?php } ?>
                                </li>
                    <?php } } ?>
                    <li class="logout nav-item">
                        <a class="nav-link" href="<?php echo site_url('main/logout'); ?>">LogOut</a>
                    </li>
                </ul>
            </div> 
        </nav>
        <!-- navbar-collapse. -->

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
        
        <!-- Breadcrums -->
        <?php $this->load->view('templates/breadcrumbs'); ?>

        <?php if($show_prodcuts==1) {?>
            <h4 class="page-section-heading text-center text-uppercase text-secondary mb-0">Product View</h4>
            <hr></hr>

            <div class="container-fluid" style="overflow: auto;">
                <?php if(!empty($products)) { ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <button onclick="return update_Sequence_Order();"class="btn btn-outline-danger float-right">Save Sequence</button>
                        </div>
                        <div class="col-lg-12">
                            <ul id="sortable" class="custom_grid_view">
                                <?php
                                $i = 1;
                                foreach ($products as $pkey => $value) { ?>
                                    <li class="item col-3 ui-state-default" id="<?php echo $value->id;?>">
                                        <div class="thumbnail">
                                            
                                            <img class="group list-group-image" src="<?php if($value->image != ''){ echo $value->image;}else{ echo base_url().'assets/img/broken-1.png'; } ?>" height="250" width="250"/>

                                            <div class="caption">
                                                <a href="#"><h6 class="group inner list-group-item-heading">
                                                    <?php echo $value->name; ?></h6></a>
                                            </div>
                                        </div>
                                    </li>
                                <?php $i++;
                                    } 
                                ?>
                            </ul>
                        </div>
                    </div>
            <?php } else { ?>
                <p class="text-center">Products not available in this category</p>
            <?php } ?>
            </div>
            <?php 
            } 
            else { ?>
                <h4 class="page-section-heading text-center text-uppercase text-secondary mb-0">Category View</h4>
            <hr></hr>
            <?php if(!empty($category_as_products)) { ?>
                <div class="container-fluid" style="overflow: auto;">
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="custom_grid_view">
                                <?php if(!empty($category_as_products)) { 
                                foreach ($category_as_products as $ckey => $cvalue) { 
                                    ?>
                                    <li class="item col-3 ui-state-default">
                                        <div class="thumbnail">

                                            <img class="group list-group-image" src="<?php if($cvalue->image_url != ''){ echo $cvalue->image_url;}else{ echo base_url().'assets/img/broken-1.png'; } ?>" height="200" width="200"/>
                                           
                                            <div class="caption">
                                                <a href="<?php echo site_url("products/index/".$cvalue->id); ?>"><h6 class="group inner list-group-item-heading">
                                                    <?php echo $cvalue->name; ?></h6></a>
                                            </div>
                                        </div>
                                    </li>
                                    <?php 
                                } 
                                } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php } 
            } ?>
    </div>
</section>

<script src="<?php echo base_url(); ?>js/custom.js"></script>
<script type="text/javascript">
    $(function () { 
    $("#sortable").sortable({
        //items: 'div.item',//'tr:not(tr:first-child)',
        cursor: 'pointer',
        //axis: 'y',
        dropOnEmpty: false,
        start: function (e, ui) {
            ui.item.addClass("selected");
        },
        update: function (e, ui) {
            ui.item.removeClass("selected");
            $('#sortable>li.item').each(function(index) {
                //$(this).children('td:first-child').html($(this).index()+1);
            });
            $("#tble_sort").sortable('refresh');
            $("#tble_sort").sortable( "refreshPositions" );     
        }
    });
});

function update_Sequence_Order() {
    $("body").addClass("loading"); 
    var selectedData = new Array();
    $('#sortable>li.item').each(function() {
        selectedData.push($(this).attr("id"));
    });
    
    $.ajax({
        url:'<?php echo base_url(); ?>products/update_squence_order',
        type:'POST',
        data:{position:selectedData},
        beforeSend: function(){
        },
        success:function(databack){
            console.log('Product order has been changed successfully');
        },complete: function(){
            alert("Product squence order changed successfully !");
            $("body").removeClass("loading");
        }
    })
}
</script>
