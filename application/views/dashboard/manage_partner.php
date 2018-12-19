<link rel="stylesheet" href="<?php echo base_url(); ?>assets/Partnerlte/plugins/datatables/dataTables.bootstrap.css">
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">List Data Partner Aktif</h3>
        <button class="btn btn-primary btn pull-right" onclick="addNewPartner()" data-toggle='modal' data-target='#modal'>Add</button>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="table" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>id</th>
               <th>Kode Partner</th>
              <th>Nama Partner</th>
              <th>Jenis Layanan</th>
              <th>Url Promo</th>
              <th>Logo Partner</th>
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
              <td><?php echo $value->bmd_id_vendor; ?></td>
              <td><?php echo $value->bmd_kode_vendor; ?></td>
              <td><?php echo $value->bmd_nama_vendor; ?></td>
              <td><?php echo $value->bmd_jenis; ?></td>
			  <td><?php echo $value->bmd_url_promo; ?></td>
              <td><img src="<?php echo base_url().'uploads/photo/'.$value->bmd_url_logo?>" class="img-responsive" width="100dp" height="100dp"> </td> 
              <td><?php echo $value->bmd_vendor_aktif; ?></td>
              <td>
                <button class="btn btn-warning btn-sm" onclick="setPartner(<?php echo $value->bmd_id_vendor; ?>)" data-toggle='modal' data-target='#modal'>Edit</button>
                <button class="btn btn-danger btn-sm" onclick="deletePartner(<?php echo $value->bmd_id_vendor; ?>)" data-toggle='modal' data-target='#modal'>Delete</button>
              </td>
            </tr>
            <?php
              }
            ?>
          </tbody>
          <tfoot>
            <tr>
             <th>#</th>
              <th>image</th>
              <th>Kode Partner</th>
              <th>Nama Partner</th>
              <th>Jenis Layanan</th>
              <th>Url Promo</th>
              <th>Logo Partner</th>
              <th>Nama Vendor</th>
             
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
  function addNewPartner() {

    $.ajax({
      type: 'POST', 
      url : '<?php echo base_url(); ?>Dashboard/add_partner',
      success : function(result) {
        $('#modal').html(result);
      }
    });
  }
  function setPartner(id) { 

    $.ajax({
      type: 'POST', 
      url : '<?php echo base_url(); ?>Dashboard/set_partner',
      data: 'id='+id,
      success : function(result) {
        $('#modal').html(result);
      }
    });
  }
  function deletePartner(id) {

    $.ajax({
      type: 'POST', 
      url : '<?php echo base_url(); ?>Dashboard/delete_partner',
      data: "id="+id,
      success : function(result) {
        $('#modal').html(result);
      }
    });
  }
</script>