<?php
$page_title = 'Reporte de ventas';
$sales = '';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
//page_require_level(3);
?>
<?php
if(isset($_POST['submit'])):
    $req_dates = array('fecha-inicio','fecha-fin');
    validate_fields($req_dates);

    if(empty($errors)):
    $start_date = remove_junk($db->escape($_POST['fecha-inicio']));
    $end_date = remove_junk($db->escape($_POST['fecha-fin']));

    if($start_date > $end_date):
        $session->msg("w", "La fecha inicio no debe ser mayor a la fecha fin.");
        redirect('sales_report.php', false);
    endif;
    
    $sales = find_sale_by_dates($start_date,$end_date);

    $data = [];
    $total_venta_reporte = 0;
    $total_utilidad_reporte = 0;
    
    foreach($sales as $sale):
        $sql = " SELECT p.name, p.sale_price, p.buy_price, sd.*,(p.buy_price * sd.qty) AS total_precio_compra,(p.sale_price * sd.qty)-(p.buy_price * sd.qty) AS utilidad_venta
                FROM sale_detail sd JOIN products p ON p.id = sd.product_id WHERE sale_id = {$sale['id']}";
        
        $productos_venta = find_by_sql($sql);

        $sale['productos'] = $productos_venta;
        array_push($data, $sale);
    endforeach;

    else:
        $session->msg("d", $errors);
        redirect('sales_report.php', false);
    endif;

else:
    $session->msg("d", "Select dates");
    redirect('sales_report.php', false);
endif;
?>

<!doctype html>
<html lang="en-US">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Reporte de ventas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
    <style>
    @media print {

        html,
        body {
            font-size: 9.5pt;
            margin: 0;
            padding: 0;
        }

        .page-break {
            page-break-before: always;
            width: auto;
            margin: auto;
        }
    }

    .page-break {
        width: auto;
        margin: 50px 12px;
    }

    .sale-head {
        margin: 40px 0;
        text-align: center;
    }

    .sale-head h1,
    .sale-head strong {
        padding: 10px 40px;
        display: block;
    }

    .sale-head h1 {
        margin: 0;
        border-bottom: 1px solid #212121;
    }

    .table>thead:first-child>tr:first-child>th {
        border-top: 1px solid #000;
    }

    table thead tr th {
        text-align: center;
        border: 1px solid #ededed;
    }

    table tbody tr td {
        vertical-align: middle;
    }

    .sale-head,
    table.table thead tr th,
    table tbody tr td,
    table tfoot tr td {
        border: 1px solid #212121;
        white-space: nowrap;
    }

    .sale-head h1,
    table thead tr th,
    table tfoot tr td {
        background-color: #f8f8f8;
    }

    tfoot {
        color: #000;
        text-transform: uppercase;
        font-weight: 500;
    }
    </style>
</head>

<body>
    <?php if($data): ?>

    <div class="page-break">
        <table style="width: 100%;" border="1">
            <thead>
                <tr>
                    <th style="border: 0px;" colspan="1">
                        <h4>
                            <img width="160" height="160" src="libs/images/logo.png" alt="">
                        </h4>
                    </th>
                    <th style="border: 0px;" colspan="2">
                    </th>
                    <th style="border: 0px;" colspan="1">
                        <h1>Reporte de ventas</h1>
                    </th>
                </tr>
                <tr style="height: 8px;">
                    <th style="border: 0px;" colspan="4">
                        <h3>Del: <?php if(isset($start_date)) { echo read_date($start_date);}?></h3>
                        <h3>Al: <?php if(isset($end_date)){echo read_date($end_date);}?></h3>
                    </th>
                </tr>

            </thead>
            <tbody>
                <table class="table table-border">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">#</th>
                            <th width="10%"> Cliente </th>
                            <th class="text-center" width="40%"> Detalle de la venta</th>
                            <th class="text-center" width="15%">Fecha de venta</th>
                            <th class="text-center" width="8%">Total de venta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $sale):?>
                        <tr>
                            <td class="text-center"><?php echo count_id();?></td>
                            <td><?php echo remove_junk($sale['customer_name']); ?></td>
                            <td class="text-center">
                                <table class="table">
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
                                    $total_venta_reporte = $total_venta_reporte + $sale['total'];
                                foreach ($sale['productos'] as $producto):
                                ?>

                                        <tr <?php echo ($bandera%2 == 0 ? 'class="warning"': 'class="info"'); ?>>
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
                                    $total_utilidad_reporte = $total_utilidad_reporte + $producto['utilidad_venta'];
                                    
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
                                                    <h4><?php echo '$'.$total_precio_compra ?></h4>
                                                </font>
                                            </td>
                                            <td class="text-center">
                                                <font color="green">
                                                    <h4><?php echo '$'.remove_junk($sale['total']); ?>
                                                    </h4>
                                                </font>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="text-center">
                                                <font color="#EE5466">
                                                    <h4>utilidad</h4>
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
                            <td class="text-center"><?php echo read_date($sale['insert_date']); ?></td>
                            <td class="text-center"><?php echo "$".remove_junk($sale['total']); ?></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                    <tfoot>
                        <tr class="text-right">
                            <td colspan="3"></td>
                            <td colspan="1"> Total </td>
                            <td> $<?php echo number_format($total_venta_reporte);?>
                            </td>
                        </tr>
                        <tr class="text-right">
                            <td colspan="3"></td>
                            <td colspan="1">Utilidad</td>
                            <td> $<?php echo $total_utilidad_reporte;?></td>
                        </tr>
                    </tfoot>
                </table>
            </tbody>
        </table>
    </div>
    <?php
else:
$session->msg("d", "No se encontraron ventas. ");
redirect('sales_report.php', false);
endif;
?>
</body>

</html>
<?php if(isset($db)) { $db->db_disconnect(); } ?>