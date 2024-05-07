<?php
$page_title = 'Reporte de ventas';
$sales = '';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
//page_require_level(3);
?>
<?php

    $sales = find_by_sql("SELECT s.id, s.customer_name, s.insert_date, s.total ,COUNT(sd.product_id) AS cantidad_productos, SUM(sd.qty) articulos_vendidos FROM sale_detail sd JOIN sale s ON s.id  = sd.sale_id WHERE s.id  = {$_GET['id']}");

    $data = [];
    $total_venta_reporte = 0;
    
    foreach($sales as $sale):
        $sql = " SELECT p.name, p.sale_price, p.buy_price, sd.*,(p.buy_price*sd.qty) AS total_precio_compra FROM sale_detail sd JOIN products p ON p.id = sd.product_id WHERE sale_id = {$sale['id']}" ;
        $productos_venta = find_by_sql($sql);
        $sale['productos'] = $productos_venta;
        array_push($data, $sale);
    endforeach;

?>

<!doctype html>
<html lang="en-US">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Reporte de ventas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
    <style>
    @media print {

        /* Se aplicará la siguiente regla CSS */
        button,
        [type="button"],
        [type="submit"],
        [type="reset"] {
            display: none;
        }

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
        width: 950px;
        margin: 30px auto;
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
    <?php if($sales): ?>
    <div class="page-break">
        <table style="width: 100%;" border="1">
            <thead>
                <tr>
                    <th style="border: 0px;" colspan="1">
                        <h4>
                            <img width="170" height="170" src="libs/images/logo.png" alt="">
                        </h4>
                    </th>
                    <th style="border: 0px;" colspan="2">
                    </th>
                    <th style="border: 0px;" colspan="1">
                        <h1>Nota de venta</h1>
                        <h3>Fecha: <?php echo date('d/m/Y'); ?></h3>
                    </th>
                </tr>
                <tr style="height: 8px;">
                    <th style="border: 0px;" colspan="4">
                        <h3>Cliente: <?php echo $sales[0]['customer_name']; ?></h3>
                        <h4>Fecha venta: <?php echo read_date($sales[0]['insert_date']); ?></h4>
                    </th>
                </tr>

            </thead>
            <tbody>
                <table class="table table-border">
                    <thead>
                        <tr>
                            <th class="text-center" width="45%">Detalle de la venta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $sale):?>
                        <tr>
                            <td class="text-center">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center"> Producto </th>
                                            <th class="text-center"> Precio unitario</th>
                                            <th class="text-center"> Unidades </th>
                                            <th class="text-center"> Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                                $total_venta_reporte = $total_venta_reporte + $sale['total'];
                                                foreach ($sale['productos'] as $producto):
                                            ?>
                                        <tr>
                                            <td class="text-center"><?php echo remove_junk($producto['name']); ?>
                                            </td>
                                            <td class="text-center">
                                                $<?php echo remove_junk($producto['sale_price']); ?>
                                            </td>
                                            <td class="text-center"><?php echo remove_junk($producto['qty']); ?>
                                            </td>
                                            <td class="text-right">
                                                $<?php echo remove_junk($producto['sale_price']*$producto['qty']); ?>
                                            </td>
                                        </tr>
                                        <?php    
                                                endforeach;
                                            ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="text-right">
                                            <td colspan="3"> Total </td>
                                            <td> $<?php echo $total_venta_reporte;?>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </td>

                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </tbody>
        </table>
        <button type="submit" name="add_product" class="btn btn-primary btn-block" onclick="window.print()">
            <span class="glyphicon glyphicon-print"></span> Imprimir
        </button>
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