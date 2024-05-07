<?php
  $page_title = 'Venta';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  
  //consulta con los productos del carrito
  $sql = "SELECT products.id AS product_id, cart.id AS cart_id, media.id AS media_id, media.file_name, media.file_type, products.name, cart.qty, products.sale_price, (cart.qty * products.sale_price) AS total
  FROM cart 
  JOIN products ON products.id = cart.product_id
  LEFT JOIN media ON media.id = products.media_id
  WHERE cart.user_id = {$_SESSION['user_id']}";
  $cart_products = find_by_sql($sql);

  $total = 0;

?>
<?php 
if(isset($_POST['add_cat'])):

    $cust_name = remove_junk($db->escape($_POST['customer-name']));

    //se buscan si existen productos en el carrito y se trae el total en caso de haber
    $sql = " SELECT IFNULL(SUM(cart.qty * products.sale_price),'empty') AS total FROM cart 
    JOIN products ON products.id = cart.product_id 
    LEFT JOIN media ON media.id = products.media_id
    WHERE cart.user_id = {$_SESSION['user_id']} ";
    
    $total_pagar = find_by_sql($sql);

    if($total_pagar[0]['total'] != 'empty'):
        //se hace toda la transacción porque si existen productos

        //inserto la venta
        $sql  = "INSERT INTO sale (user_id, customer_name, total, insert_date)";
        $sql .= " VALUES ('{$_SESSION['user_id']}', '{$cust_name}', {$total_pagar[0][total]}, NOW())";
        $rs = $db->query($sql);
    
        if($rs)://verifico si se registró el encabezado de la venta
            //traigo el último id insertado de la venta
            $sql = "SELECT id FROM sale ORDER BY id DESC LIMIT 1";
            $ultimo_id = find_by_sql($sql);
    
            //inserto los productos en el detalle
            $sql = "INSERT INTO sale_detail (sale_id, product_id, qty, total, insert_date) ";
            $sql .= " (SELECT {$ultimo_id[0]['id']} AS sale_id, products.id, cart.qty, (cart.qty * products.sale_price) AS total, NOW() AS today";
            $sql .= " FROM cart JOIN products ON products.id = cart.product_id";
            $sql .= " WHERE cart.user_id = {$_SESSION['user_id']})";
            $rs = $db->query($sql);
            
            //elimino los registros de la tabla cart
            $delete = find_by_sql("DELETE FROM cart WHERE user_id = {$_SESSION['user_id']};");
    
            if($rs && (!is_null($delete) || !empty($delete))):
                $session->msg("s", "Venta realizada correctamente.");
            else:
                $session->msg("d", "Lo siento, error al registrar los productos al detalle de la venta. ".$delete);
            endif;

            redirect('sale_detail.php',false);
        else:
            $session->msg("d", "Lo siento, error al registrar la venta.");
            redirect('sale_detail.php',false);
        endif;
    else://else validación de productos en el carrito
        $session->msg("w", "No hay productos en el carrito.");
        redirect('sale_detail.php',false);
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
        <div class="panel panel-default clearfix">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-shopping-cart"></span>
                    <span>Detalle de venta</span>
                </strong>
                <a href="add_cart.php" class="btn btn-primary pull-right btn-sm">Agregar productos a carrito</a>
            </div>

            <form action="sale_detail.php" method="post">
                <div class="panel-body">
                    <div class="form-group">
                        <b>Nombre cliente <font color="red">*</font></b>
                        <input type="text" class="form-control" name="customer-name" placeholder="Nombre del cliente"
                            required>
                    </div>
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th class="text-center">Imagen</th>
                                <th class="text-center">Producto</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-center">Precio unitario</th>
                                <th class="text-center">Subtotal</th>
                                <th class="text-center">Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_products as $product):?>
                            <tr>
                                <td class="text-center"><?php echo count_id();?></td>
                                <td class="text-center">
                                    <?php if($product['media_id'] === '0'): ?>
                                    <img class="img-avatar img-circle" src="uploads/products/no_image.jpg" alt="">
                                    <?php else: ?>
                                    <img class="img-avatar img-circle"
                                        src="uploads/products/<?php echo $product['file_name']; ?>" alt="">
                                    <?php endif; ?>
                                </td>
                                <td><?php echo remove_junk(ucfirst($product['name'])); ?></td>
                                <td class="text-center"><?php echo remove_junk(ucfirst($product['qty'])); ?></td>
                                <td class="text-center"><?php echo remove_junk(ucfirst($product['sale_price'])); ?></td>
                                <td class="text-center"><?php echo remove_junk(ucfirst($product['total'])); ?></td>
                                <td class="text-center">
                                    <a href="delete_product_sale_detail.php?product_id=<?php echo intval($product['product_id']);?>"
                                        class="btn btn-sm btn-danger" data-toggle="tooltip" title="Eliminar producto del carrito">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </a>
                                </td>
                            </tr>
                            <?php 
                            $total = doubleval($total + $product['total']);
                            endforeach; 
                        ?>
                        </tbody>
                        <footer>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center" colspan="2">
                                <font color="green">
                                    <h4>Total a cobrar</h4>
                                </font>
                            </td>
                            <td class="text-center">
                                <font color="green">
                                    <h4 name="total">$<?php echo $total ?></h4>
                                </font>
                            </td>
                            <td></td>
                        </footer>
                    </table>
                    <button type="submit" name="add_cat" class="btn btn-success">Finalizar venta</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<?php include_once('layouts/footer.php'); ?>