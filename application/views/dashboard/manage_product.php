<link rel="stylesheet" href="<?php echo base_url(); ?>assets/Partnerlte/plugins/datatables/dataTables.bootstrap.css">
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">List Data Produk</h3>
        <button class="btn btn-primary btn pull-right" onclick="addNewProduct()" data-toggle='modal' data-target='#modal'>Add</button>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="table" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama Partner</th>
              <th>Nama Paket</th>
              <th>Deskripsi</th>
              <th>harga</th>
			  <th>Narahubung</th>
			  <th>Gambar Promo</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $counter = 1;
              foreach($data as $value) {
            ?>
            <tr>
              <td><?php echo $counter++; ?></td>
              <td><?php echo $value->bmd_nama_vendor; ?></td>
              <td><?php echo $value->bmd_nama_paket; ?></td>
              <td><?php echo $value->bmd_deskripsi; ?></td>
              <td><?php echo $value->bmd_harga; ?></td>
              <td><?php echo $value->bmd_contact_person; ?></td>
			  <td> <img src=" <?php echo base_url().'uploads/photo/'.$value->bmd_url_gambarpromo?>" class="image-responsive" width="100" height="100"> </td>
              <td><?php echo $value->bmd_aktif; ?></td>
              <td>
                <button class="btn btn-warning btn-sm" onclick="setProduct(<?php echo $value->bmd_id; ?>)" data-toggle='modal' data-target='#modal'>Edit</button>
                <button class="btn btn-danger btn-sm" onclick="deleteProduct(<?php echo $value->bmd_id; ?>)" data-toggle='modal' data-target='#modal'>Delete</button>
              </td>
            </tr>
            <?php
              }
            ?>
          </tbody>
          <tfoot>
            <tr>
             <th>#</th>
               <th>Nama Produk</th>
              <th>Nama Paket</th>
              <th>Deskripsi</th>
                <th>harga</th>
              <th>Narahubung</th>
              <th>Status</th>
             
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->
<div class="modal fade" id="modal">
</div>

<script>
  function addNewProduct() {

    $.ajax({
      type: 'POST', 
      url : '<?php echo base_url(); ?>dashboard/add_product',
      success : function(result) {
        $('#modal').html(result);
      }
    });
  }
  
  
  
  function setProduct(id) { 

    $.ajax({
      type: 'POST', 
      url : '<?php echo base_url(); ?>Dashboard/set_product',
      data: 'id='+id,
      success : function(result) {
        $('#modal').html(result);
      }
    });
  }
  function deleteProduct(id) {

    $.ajax({
      type: 'POST', 
      url : '<?php echo base_url(); ?>Dashboard/delete_product',
      data: "id="+id,
      success : function(result) {
        $('#modal').html(result);
      }
    });
  }
</script>