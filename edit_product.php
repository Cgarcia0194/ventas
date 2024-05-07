<?php
  $page_title = 'Editar producto';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php
$product = find_by_id('products',(int)$_GET['id']);
$all_categories = find_all('categories');
$all_photo = find_all('media');
if(!$product){
  $session->msg("d","Missing product id.");
  redirect('product.php');
}
?>
<?php
 if(isset($_POST['product'])){
    $req_fields = array('product-title','product-categorie','product-quantity','buying-price', 'saleing-price' );
    validate_fields($req_fields);

   if(empty($errors)){
       $p_name  = remove_junk($db->escape($_POST['product-title']));
       $p_cat   = (int)$_POST['product-categorie'];
       $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
       $p_buy   = remove_junk($db->escape($_POST['buying-price']));
       $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
       if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
         $media_id = '0';
       } else {
         $media_id = remove_junk($db->escape($_POST['product-photo']));
       }
       $query   = "UPDATE products SET";
       $query  .=" name ='{$p_name}', quantity ='{$p_qty}',";
       $query  .=" buy_price ='{$p_buy}', sale_price ='{$p_sale}', categorie_id ='{$p_cat}',media_id='{$media_id}'";
       $query  .=" WHERE id ='{$product['id']}'";
       $result = $db->query($query);
               if($result && $db->affected_rows() === 1){
                 $session->msg('s',"Producto ha sido actualizado. ");
                 redirect('product.php', false);
               } else {
                 $session->msg('d',' Lo siento, actualización falló.');
                 redirect('edit_product.php?id='.$product['id'], false);
               }

   } else{
       $session->msg("d", $errors);
       redirect('edit_product.php?id='.$product['id'], false);
   }

 }

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-pencil"></span>
                    <span>Editar producto</span>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="edit_product.php?id=<?php echo (int)$product['id'] ?>" class="clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <b>Categoría <font color="red">*</font></b>
                            <select class="form-control" name="product-categorie">
                                <option value="">Selecciona una categoría</option>
                                <?php  foreach ($all_categories as $cat): ?>
                                <option value="<?php echo (int)$cat['id']; ?>"
                                    <?php if($product['categorie_id'] === $cat['id']): echo "selected"; endif; ?>>
                                    <?php echo remove_junk($cat['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <b>Imagen <font color="red">*</font></b>
                            <select class="form-control" name="product-photo">
                                <option value=""> Sin imagen</option>
                                <?php  foreach ($all_photo as $photo): ?>
                                <option value="<?php echo (int)$photo['id'];?>"
                                    <?php if($product['media_id'] === $photo['id']): echo "selected"; endif; ?>>
                                    <?php echo $photo['file_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <b>Descripción <font color="red">*</font></b>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-th-large"></i>
                                </span>
                                <input type="text" class="form-control" name="product-title"
                                    value="<?php echo remove_junk($product['name']);?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                        <div class="form-group">
                            <b>Cantidad <font color="red">*</font></b>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-shopping-cart"></i>
                                </span>
                                <input type="number" class="form-control" name="product-quantity"
                                    value="<?php echo remove_junk($product['quantity']); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                        <div class="form-group">
                            <b>Precio de compra <font color="red">*</font></b>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-usd"></i>
                                </span>
                                <input type="number" class="form-control" name="buying-price"
                                    value="<?php echo remove_junk($product['buy_price']);?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                        <div class="form-group">
                            <b>Precio de venta <font color="red">*</font></b>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-usd"></i>
                                </span>
                                <input type="number" class="form-control" name="saleing-price"
                                    value="<?php echo remove_junk($product['sale_price']);?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <button type="submit" name="product" class="btn btn-primary">Actualizar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>