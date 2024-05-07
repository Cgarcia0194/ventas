<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  $product = find_by_id('products',(int)$_GET['id']);
  if(!$product){
    $session->msg("d","ID vacío");
    redirect('product.php');
  }

//validar si la el producto ya se ha vendido
$sql = "SELECT IFNULL(COUNT(product_id),0) AS numero_producto_venta FROM sale_detail sd WHERE sd.product_id = {$_GET['id']}";
$productos_venta = find_by_sql($sql);

if(intval($productos_venta[0]['numero_producto_venta']) > 0){
  $session->msg("w","No puedes eliminar el producto una vez que se asoció a una venta.");
  redirect('product.php');
}else{
  $delete_id = delete_by_id('products',(int)$product['id']);
  
  if($delete_id){
      $session->msg("s","Producto eliminado");
      redirect('product.php');
  } else {
      $session->msg("d","Eliminación falló");
      redirect('product.php');
  }
}


?>