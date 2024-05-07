<?php
  $page_title = 'Lista de imagenes';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php $media_files = find_all('media');?>
<?php
  if(isset($_POST['submit'])) {
  $photo = new Media();
  $photo->upload($_FILES['file_upload']);
    if($photo->process_media()){
        $session->msg('s','Imagen subida correctamente al servidor.');
        redirect('media.php');
    } else{
      $session->msg('d',join($photo->errors));
      redirect('media.php');
    }

  }

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php echo display_msg($msg); ?>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <span class="glyphicon glyphicon-camera"></span>
                <span>Lista de imagenes</span>
            </div>
            <div class="panel-body">
                <form action="media.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <b>Imagen <font color="red">*</font></b>
                        <input type="file" name="file_upload" multiple="multiple" placeholder="Seleccione un archivo..."
                            class="form-control" />
                    </div>
                    <button type="submit" name="submit" class="btn btn-success">Agregar</button>
                </form>
            </div>

            <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Imagen</th>
                            <th class="text-center">Descripción</th>
                            <th class="text-center">Tipo</th>
                            <th class="text-center">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($media_files as $media_file): ?>
                        <tr class="list-inline">
                            <td class="text-center"><?php echo count_id();?></td>
                            <td class="text-center">
                                <img src="uploads/products/<?php echo $media_file['file_name'];?>"
                                    class="img-thumbnail" />
                            </td>
                            <td class="text-center">
                                <?php echo $media_file['file_name'];?>
                            </td>
                            <td class="text-center">
                                <?php echo $media_file['file_type'];?>
                            </td>
                            <td class="text-center">
                                <a href="delete_media.php?id=<?php echo (int) $media_file['id'];?>"
                                    class="btn btn-danger btn-sm" title="Eliminar">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
            </div>
        </div>
    </div>
</div>


<?php include_once('layouts/footer.php'); ?>