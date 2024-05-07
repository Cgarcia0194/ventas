<?php
  $page_title = 'Agregar al carrito';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php

if(isset($_POST['add_cart'])):

    $req_fields = array('s_id','quantity','price','total', 'date' );
    validate_fields($req_fields);
    
    if(empty($errors)):
          $p_id      = $db->escape(intval($_POST['s_id']));
          $s_qty     = $db->escape(intval($_POST['quantity']));
          //$s_total   = $db->escape($_POST['total']);
          $date      = $db->escape($_POST['date']);
          $s_date    = make_date();

        if($s_qty <= 0):
            $session->msg("d", 'Debes ingresar cantidades mayores a 0.');
            redirect('add_cart.php',false);
        else:
            /**
            * Sirve para verificar la existencia del producto
            */
            $sql = "SELECT quantity FROM products WHERE id = ".$p_id;
            $cantidadProducto = find_by_sql($sql);

            if(doubleval($cantidadProducto[0]['quantity']) == 0)://el articulo ya no tierne stock
                $session->msg("d", 'El artículo ya no tiene stock.');
                redirect('add_cart.php',false);    
            elseif(doubleval($cantidadProducto[0]['quantity']) < $s_qty)://la cantidad a solicitar es mayor al stock
                $session->msg("d", "Solo quedan {$cantidadProducto[0]['quantity']} artículos.");
                redirect('add_cart.php',false);
            endif;

            /**
             * Verificar si ya hay registro en el carrito
             */
             $sql = "SELECT id,IFNULL(qty,0) AS qty FROM cart WHERE product_id = {$p_id} AND user_id = {$_SESSION['user_id']}";
             $inforCarrito = find_by_sql($sql);

            if(!is_null($inforCarrito) && !empty($inforCarrito)):
                $cantidadFinal = doubleval($inforCarrito[0]['qty'] + $s_qty);
                $sql = "UPDATE cart SET qty = {$cantidadFinal} WHERE product_id = {$p_id} AND user_id = {$_SESSION['user_id']}"; 

                $result = $db->query($sql);
                
                if($result && $db->affected_rows() === 1):
                    update_product_qty($s_qty,$p_id);
                    $session->msg("s", "El carrito se actualizó correctamente.");
                    redirect('add_cart.php', false);
                else:
                    $session->msg("d", "Lo siento, error al actualizar.");
                    redirect('add_cart.php', false);
                endif;
            else:
                $sql  = "INSERT INTO cart (product_id, user_id , qty, date)";
                $sql .= " VALUES ('{$p_id}','{$_SESSION['user_id']}','{$s_qty}','{$s_date}')";
                //$sql .= "'{$p_id}','{$_SESSION['user_id']}','{$s_qty}','{$s_total}','{$s_date}'";

                if($db->query($sql)):
                    update_product_qty($s_qty,$p_id);
                    $session->msg('s',"Producto agregado al carrito");
                    redirect('add_cart.php', false);
                else:
                    $session->msg('d','Lo siento, registro falló.');
                    redirect('add_cart.php', false);
                endif;
            endif;
        endif;
    else:
        $session->msg("d", $errors);
        redirect('add_cart.php',false);
    endif;
endif;
?>

<?php include_once('layouts/header.php');?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php echo display_msg($msg); ?>
        <form method="post" action="ajax.php" autocomplete="off" id="sug-form">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-info">Búsqueda</button>
                    </span>
                    <input type="text" id="sug_input" class="form-control" name="title"
                        placeholder="Buscar por el nombre del producto">
                </div>
                <div id="result" class="list-group"></div>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-shopping-cart"></span>
                    <span>Carrito</span>
                    <a href="sale_detail.php" class="btn btn-primary pull-right btn-sm">Ir a carrito</a>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="add_cart.php">
                    <table class="table table-bordered">
                        <thead>
                            <th> Producto </th>
                            <th> Precio </th>
                            <th> Cantidad </th>
                            <th> Total </th>
                            <th> Agregado</th>
                            <th> Acciones</th>
                        </thead>
                        <tbody id="product_info"> </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>

</div>

<?php include_once('layouts/footer.php'); ?>