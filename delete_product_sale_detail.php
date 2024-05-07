<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);

  //traigo la cantidad que hay en el carrito
  $cart_product = find_by_sql( "SELECT * FROM cart WHERE user_id = {$_SESSION['user_id']} AND product_id = {$_GET['product_id']};");;
  if(!$cart_product){
    $session->msg("d","ID vacío");
    redirect('sale_detail.php');
  }

//actualizo el stock con lo que hay más lo que se va eliminar del carrito
$stock_product = find_by_sql("UPDATE products SET quantity = quantity + {$cart_product[0]['qty']} WHERE id = {$cart_product[0]['product_id']};");

//elimino el producto de la tabla cart
$delete = find_by_sql("DELETE FROM cart WHERE user_id = {$_SESSION['user_id']} AND product_id = {$cart_product[0]['product_id']};");

  if(!is_null($delete) || !empty($delete)){
      $session->msg("s","Producto eliminado del carrito correctamente");
      redirect('sale_detail.php');
  } else {
      $session->msg("d","Eliminación falló");
      redirect('sale_detail.php');
  }
?>