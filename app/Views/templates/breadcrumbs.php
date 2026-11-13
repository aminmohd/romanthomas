    
    <!-- Breadcrums -->
        <?php if($main_CategoryName != ''){?>
        <ul class="breadcrumb">
            <?php if($categoryName != ''){?>
            <li><a href="<?php echo site_url("products/index/".$main_CategoryID); ?>"> <?php } ?>
                <?php if($main_CategoryName=='Inventory') 
                    { echo 'the SHOP';} 
                else 
                    { echo $main_CategoryName; } 
                if($categoryName != ''){?>   </a>    <?php } ?>
                </li>

                <?php if($categoryName != ''){?>
            <li><?php if($subcatName != ''){?><a href="<?php echo site_url("products/index/".$categoryID); ?>"><?php } ?><?= $categoryName ;?><?php if($subcatName != ''){?></a><?php } ?></li>

          <?php if($subcatName == ''){}else{?><li><?= $subcatName; ?> </li><?php } ?>
            <?php } ?>
        </ul>
        <?php } ?>