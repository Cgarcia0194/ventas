<?php
  $page_title = 'Lista de ventas';
  require_once('includes/load.php');
  include_once('includes/functions.php');
  
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php
$sales = find_all_sale();

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-6">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Todas la ventas</span>
                </strong>
                <div class="pull-right">
                    <a href="add_cart.php" class="btn btn-primary">Agregar venta</a>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th> Cliente </th>
                            <th class="text-center"> Cantidad de artículos de venta</th>
                            <th class="text-center">Total de venta</th>
                            <th class="text-center">Fecha de venta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sales as $sale):?>
                        <tr>
                            <td class="text-center"><?php echo count_id();?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="sale_detail_print.php?id=<?php echo intval($sale['id']);?>"
                                         title="Imprimir venta" data-toggle="tooltip">
                                        <?php echo remove_junk($sale['customer_name']); ?>
                                    </a>
                                </div>
                            </td>
                            
                            <td class="text-center"><?php echo (int)$sale['articulos_vendidos']; ?></td>
                            <td class="text-center"><?php echo '$'.remove_junk($sale['total']); ?></td>
                            <td class="text-center"><?php echo read_date($sale['insert_date']); ?></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>