<?php
  $page_title = 'Agregar producto';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $all_categories = find_all('categories');
  $all_photo = find_all('media');
?>
<?php
 if(isset($_POST['add_product'])):
   $req_fields = array('categoría','imagen','descripción','cantidad', 'precio-de-compra', 'precio-de-venta');
   validate_fields($req_fields);

   if(empty($errors)):
    $p_cat   = remove_junk($db->escape($_POST['categoría'])); 
    $p_name  = remove_junk($db->escape($_POST['descripción']));
     $p_qty   = remove_junk($db->escape($_POST['cantidad']));
     $p_buy   = remove_junk($db->escape($_POST['precio-de-compra']));
     $p_sale  = remove_junk($db->escape($_POST['precio-de-venta']));

     if (is_null($_POST['imagen']) || $_POST['imagen'] === "") :
       $media_id = '0';
     else:
       $media_id = remove_junk($db->escape($_POST['imagen']));
     endif;
     
     $date    = make_date();
     $query  = "INSERT INTO products (";
     $query .=" name, quantity, buy_price, sale_price, categorie_id, media_id,date";
     $query .=") VALUES (";
     $query .=" '{$p_name}', '{$p_qty}', '{$p_buy}', '{$p_sale}', '{$p_cat}', '{$media_id}', '{$date}'";
     $query .=")";
     $query .=" ON DUPLICATE KEY UPDATE name='{$p_name}'";
     if($db->query($query)):
       $session->msg('s',"Producto agregado exitosamente. ");
       redirect('product.php', false);
     else:
       $session->msg('d',' Lo siento, error al registrar el producto.');
       redirect('product.php', false);
     endif;

    else:
     $session->msg("d", $errors);
     redirect('add_product.php',false);
    endif;

  endif;

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
                    <span class="glyphicon glyphicon-floppy-disk"></span>
                    <span>Agregar producto</span>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="add_product.php" class="clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <b>Categoría <font color="red">*</font></b>
                            <select class="form-control" name="categoría">
                                <option value="">Selecciona una categoría</option>
                                <?php  foreach ($all_categories as $cat): ?>
                                <option value="<?php echo (int)$cat['id'] ?>">
                                    <?php echo $cat['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <b>Imagen <font color="red">*</font></b>
                            <select class="form-control" name="imagen">
                                <option value="">Selecciona una imagen</option>
                                <?php  foreach ($all_photo as $photo): ?>
                                <option value="<?php echo (int)$photo['id'] ?>">
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
                                <input type="text" class="form-control" name="descripción" placeholder="Descripción">
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
                                <input type="number" class="form-control" name="cantidad" placeholder="Cantidad">
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
                                <input type="number" step="any" class="form-control" name="precio-de-compra"
                                    placeholder="Precio de compra">
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
                                <input type="number" step="any" class="form-control" name="precio-de-venta"
                                    placeholder="Precio de venta">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <button type="submit" name="add_product" class="btn btn-success">Agregar</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>