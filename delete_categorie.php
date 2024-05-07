<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>
<?php
  $categorie = find_by_id('categories',(int)$_GET['id']);
  if(!$categorie){
    $session->msg("d","ID de la categoría falta.");
    redirect('categorie.php');
  }
  
//validar si la categoría ya tiene productos
$sql = "SELECT IFNULL(COUNT(id),0) AS numero_productos FROM products p WHERE categorie_id = {$_GET['id']}";
$productos_categoria = find_by_sql($sql);

if(intval($productos_categoria[0]['numero_productos']) > 0){
  $session->msg("w","No puedes eliminar la categoría una vez asignada a un producto.");
  redirect('categorie.php');
}else{
  $delete_id = delete_by_id('categories',(int)$categorie['id']);

  if($delete_id){
    $session->msg("s","Categoría eliminada");
    redirect('categorie.php');
  } else {
    $session->msg("d","Eliminación falló.");
    redirect('categorie.php');
  }
}
  
?>