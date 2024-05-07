<div id="product-grid">
    <div class="txt-heading">
        <div class="txt-heading-label">Products</div>
    </div>
    <?php
    $query = "SELECT * FROM tbl_product";
    $product_array = $shoppingCart->getAllProduct($query);
    if (! empty($product_array)) {
		?>
        <form method="post"
            action="index.php?action=add">	
            <div>
        	<?php
        foreach ($product_array as $key => $value) {
            ?>
        <div class="product-item" id="product-item-<?php echo $product_array[$key]["id"]; ?>">
        
			
            <div class="product-image">
    <img src="<?php echo $product_array[$key]["image"]; ?>" onclick="selectImage(<?php echo $product_array[$key]["id"] ?>);"/>
                <div class="product-title">
                 <input type="checkbox" class="checkstatus" name="chk_product[]" data-id="<?php echo $product_array[$key]["id"]; ?>" data-code="<?php echo $product_array[$key]["code"]; ?>"><?php echo $product_array[$key]["name"]; ?></br>
			<?php echo "$".$product_array[$key]["price"]; ?></br>
			</div>
            </div>
			
			
			<input type="hidden" name="selected_product[]" id="product-<?php echo $product_array[$key]["id"]?>" value="" />
			
       
    </div>
    <?php
        }
		?>
         </div>
		<div>
        <input type="submit" name="add_to_cart" value="Add to Cart" class="btn-submit" />
        </div>
         </form>
		<?php
    }
    ?>
</div>
<script src="jquery-3.2.1.min.js"></script>

<script>
	$("input[type=checkbox]").on("click", function() {
		var product_id = $(this).data("id");
		var product_code = $(this).data("code");


        	if($(this).is(":checked")){
            	$("#product-item-"+product_id).css("border","#d96557 3px solid");
            	$("#product-"+product_id).val(product_code);
        	}
        	else if($(this).is(":not(:checked)")){
            	$("#product-item-"+product_id).css("border","none");
            	$("#product-"+product_id).val("	");
        	}
	});
</script>