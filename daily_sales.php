<?php
  $page_title = 'Venta diaria';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);

 $data = [];
 $sales = dailySales();
 
 foreach($sales as $sale):
  //$sql = " SELECT p.name ,sd.* FROM sale_detail sd JOIN products p ON p.id = sd.product_id WHERE sale_id  = {$sale['id']}" ;
  $sql = " SELECT p.name, p.sale_price, p.buy_price, sd.*,(p.buy_price*sd.qty) AS total_precio_compra FROM sale_detail sd JOIN products p ON p.id = sd.product_id WHERE sale_id = {$sale['id']}" ;

  $productos_venta = find_by_sql($sql);
  $sale['productos'] = $productos_venta;
  array_push($data, $sale);
 endforeach;

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
                    <span>Venta diaria</span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">#</th>
                            <th width="15%"> Cliente </th>
                            <th class="text-center" width="45%"> Detalle de la venta</th>
                            <th class="text-center" width="10%">Total de venta</th>
                            <th class="text-center" width="15%">Fecha de venta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $sale):?>
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
                            <td class="text-center">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center"> Producto </th>
                                            <th class="text-center"> Unidades </th>
                                            <th class="text-center"> Precio de compra unidad </th>
                                            <th class="text-center"> Precio de venta unidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $bandera = 1;
                                        $total_unidades = 0;
                                        $total_precio_compra = 0;
                                        foreach ($sale['productos'] as $producto):
                                        ?>

                                        <tr <?php echo ($bandera%2 == 0 ? 'class="info"': 'class="warning"'); ?>>
                                            <td class="text-center"><?php echo remove_junk($producto['name']); ?></td>
                                            <td class="text-center"><?php echo remove_junk($producto['qty']); ?></td>
                                            <td class="text-center"><?php echo remove_junk($producto['buy_price']); ?>
                                            </td>
                                            <td class="text-center"><?php echo remove_junk($producto['sale_price']); ?>
                                            </td>
                                        </tr>

                                        <?php 
                                            $bandera = $bandera + 1;
                                            $total_unidades = $total_unidades + $producto['qty'];
                                            $total_precio_compra = $total_precio_compra + $producto['total_precio_compra'];
                                        endforeach;
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="text-center">
                                                <font color="green">
                                                    <h4>Total</h4>
                                                </font>
                                            </td>
                                            <td class="text-center">
                                                <font color="green">
                                                    <h4><?php echo $total_unidades ?></h4>
                                                </font>
                                            </td>
                                            <td class="text-center">
                                                <font color="green">
                                                    <h4 name="total"><?php echo '$'.$total_precio_compra ?></h4>
                                                </font>
                                            </td>
                                            <td class="text-center">
                                                <font color="green">
                                                    <u>
                                                        <h4 name="total"><?php echo '$'.remove_junk($sale['total']); ?>
                                                        </h4>
                                                    </u>
                                                </font>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="text-center">
                                                <font color="#EE5466">
                                                    <h4>Utilidad</h4>
                                                </font>
                                            </td>
                                            <td colspan="2" class="text-center">
                                                <font color="#EE5466">
                                                    <h4><?php echo '$'.($sale['total']-$total_precio_compra) ?></h4>
                                                </font>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </td>
                            <td class="text-center"><?php echo "$".remove_junk($sale['total']); ?></td>
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