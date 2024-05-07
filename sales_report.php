<?php
$page_title = 'Reporte de ventas';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel">
            <div class="panel-heading">

            </div>
            <div class="panel-body">
                <form class="clearfix" method="post" action="sale_report_process.php">
                    <div class="form-group">
                        <label class="form-label">Rango de fechas</label>
                        <div class="input-group">
                            <input type="text" class="datepicker form-control" name="fecha-inicio"
                                placeholder="Fecha inicio">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-menu-right"></i></span>
                            <input type="text" class="datepicker form-control" name="fecha-fin" placeholder="Fecha fin">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-success">Generar Reporte</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</div>
<?php include_once('layouts/footer.php'); ?>