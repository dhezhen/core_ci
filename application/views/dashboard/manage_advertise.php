<link rel="stylesheet" href="<?php echo base_url(); ?>assets/Partnerlte/plugins/datatables/dataTables.bootstrap.css">
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">List Data Advertiser</h3>
        <button class="btn btn-primary btn pull-right" onclick="addNewAdvertiser()" data-toggle='modal' data-target='#modal'>Add</button>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="table" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Advertiser</th>
              <th>Tanggal Mulai</th>
              <th>Tanggal Berakhir</th>
              <th>Harga</th>
			  <th>Link</th>
			  <th>Gambar Iklan</th>
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
              <td><?php echo $value->bmad_name; ?></td>
              <td><?php echo $value->bmad_date_start; ?></td>
              <td><?php echo $value->bmad_date_end; ?></td>
              <td><?php echo $value->bmad_harga; ?></td>
              <td><?php echo $value->bmad_link; ?></td>
			  <td> <img src=" <?php echo base_url().'uploads/photo/'.$value->bmad_image?>" class="image-responsive" width="100" height="100"> </td>
              <td><?php echo $value->bmad_status; ?></td>
                <td>
                <button class="btn btn-warning btn-sm" onclick="setAdvertiser(<?php echo $value->bmad_id; ?>)" data-toggle='modal' data-target='#modal'>Edit</button>
                <button class="btn btn-danger btn-sm" onclick="deleteAdvertiser(<?php echo $value->bmad_id; ?>)" data-toggle='modal' data-target='#modal'>Delete</button>
              </td>
            </tr>
            <?php
              }
            ?>
          </tbody>
          <tfoot>
            <tr>
             <th>#</th>
              <th>Advertiser</th>
              <th>Tanggal Mulai</th>
              <th>Tanggal Berakhir</th>
              <th>Harga</th>
			  <th>Link</th>
			  <th>Gambar Iklan</th>
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
  function addNewAdvertiser() {

    $.ajax({
      type: 'POST', 
      url : '<?php echo base_url(); ?>dashboard/add_advertiser',
      success : function(result) {
        $('#modal').html(result);
      }
    });
  }
 
  function setAdvertiser(id) { 

    $.ajax({
      type: 'POST', 
      url : '<?php echo base_url(); ?>Dashboard/set_advertiser',
      data: 'id='+id,
      success : function(result) {
        $('#modal').html(result);
      }
    });
  }
  function deleteAdvertiser(id) {

    $.ajax({
      type: 'POST', 
      url : '<?php echo base_url(); ?>dashboard/delete_advertiser',
      data: "id="+id,
      success : function(result) {
        $('#modal').html(result);
      }
    });
  }
</script>