<?php
  $page_title = 'Lista de productos';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  $products = join_product_table();
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-list"></span>
                    <span>Productos</span>
                </strong>
                <a href="add_product.php" class="btn btn-primary pull-right btn-sm">Agregar producto</a>
            </div>
            <div class="panel-body">
                <script language="javascript">
                function doSearch() {
                    var tableReg = document.getElementById('regTable');
                    var searchText = document.getElementById('searchTerm').value.toLowerCase();
                    for (var i = 1; i < tableReg.rows.length; i++) {
                        var cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
                        var found = false;
                        for (var j = 0; j < cellsOfRow.length && !found; j++) {
                            var compareWith = cellsOfRow[j].innerHTML.toLowerCase();
                            if (searchText.length == 0 || (compareWith.indexOf(searchText) > -1)) {
                                found = true;
                            }
                        }
                        if (found) {
                            tableReg.rows[i].style.display = '';
                        } else {
                            tableReg.rows[i].style.display = 'none';
                        }
                    }
                }
                </script>

                <table class="table table-bordered" id="regTable">
                    <thead>
                        <tr>
                            <th colspan="1">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-search"></i>
                                </span>
                            </th>
                            <th colspan="9">
                                <input id="searchTerm" type="text" class="form-control" name="product-search"
                                    placeholder="Buscar" onkeyup="doSearch()" />
                            </th>
                        </tr>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th> Imagen</th>
                            <th> Descripción </th>
                            <th class="text-center" style="width: 10%;"> Categoría </th>
                            <th class="text-center" style="width: 10%;"> Stock </th>
                            <th class="text-center" style="width: 10%;"> Precio de compra </th>
                            <th class="text-center" style="width: 10%;"> Precio de venta </th>
                            <th class="text-center" style="width: 10%;"> Agregado </th>
                            <th class="text-center" style="width: 10%;"> Editar </th>
                            <th class="text-center" style="width: 10%;"> Eliminar </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product):?>
                        <tr>
                            <td class="text-center"><?php echo count_id();?></td>
                            <td>
                                <?php if($product['media_id'] === '0'): ?>
                                <img class="img-avatar img-circle" src="uploads/products/no_image.jpg" alt="">
                                <?php else: ?>
                                <img class="img-avatar img-circle"
                                    src="uploads/products/<?php echo $product['image']; ?>" alt="">
                                <?php endif; ?>
                            </td>
                            <td> <?php echo remove_junk($product['name']); ?></td>
                            <td class="text-center"> <?php echo remove_junk($product['categorie']); ?></td>
                            <td class="text-center"> <?php echo remove_junk($product['quantity']); ?></td>
                            <td class="text-center"> <?php echo remove_junk($product['buy_price']); ?></td>
                            <td class="text-center"> <?php echo remove_junk($product['sale_price']); ?></td>
                            <td class="text-center"> <?php echo read_date($product['date']); ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="edit_product.php?id=<?php echo (int)$product['id'];?>"
                                        class="btn btn-warning btn-sm" title="Editar" data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </a>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="delete_product.php?id=<?php echo (int)$product['id'];?>"
                                        class="btn btn-danger btn-sm" title="Eliminar" data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>