<link rel="stylesheet" href="<?php echo base_url(); ?>assets/adminlte/plugins/datatables/dataTables.bootstrap.css">
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">List</h3>
        <button class="btn btn-primary btn pull-right" onclick="addNewUser()" data-toggle='modal' data-target='#modal'>Add</button>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="table" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Full Name</th>
              <th>Email</th>
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
              <td><?php echo $value->user_name; ?></td>
              <td><?php echo $value->user_login; ?></td>
              <td>
                <button class="btn btn-warning btn-sm" onclick="setUser(<?php echo $value->user_id; ?>)" data-toggle='modal' data-target='#modal'>Edit</button>
                <button class="btn btn-danger btn-sm" onclick="deleteUser(<?php echo $value->user_id; ?>)" data-toggle='modal' data-target='#modal'>Delete</button>
              </td>
            </tr>
            <?php
              }
            ?>
          </tbody>
          <tfoot>
            <tr>
              <th>#</th>
              <th>Email</th>
              <th>Name</th>
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




  function addNewUser() {

    $.ajax({
      type: 'POST', 
      url : '<?php echo base_url(); ?>admin/add_user',
      success : function(result) {
        $('#modal').html(result);
      }
    });
  }
  function setUser(id) { 

    $.ajax({
      type: 'POST', 
      url : '<?php echo base_url(); ?>admin/set_user',
      data: 'id='+id,
      success : function(result) {
        $('#modal').html(result);
      }
    });
  }
  
  function deleteUser(id) {

    $.ajax({
      type: 'POST', 
      url : '<?php echo base_url(); ?>admin/delete_user',
      data: "id="+id,
      success : function(result) {
        $('#modal').html(result);
      }
    });
  }
</script>