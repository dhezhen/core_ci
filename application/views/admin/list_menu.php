<link rel="stylesheet" href="<?php echo base_url(); ?>assets/adminlte/plugins/datatables/dataTables.bootstrap.css">
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">List</h3>
        <button class="btn btn-primary btn pull-right" onclick="addNewMenu()" data-toggle='modal' data-target='#modal'>Add</button>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="table" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Module</th>
              <th>Menu</th>
              <th>Link</th>
              <th>Weight</th>
              <th>Icon</th>
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
              <td><?php echo $value->module; ?></td>
              <td><?php echo $value->menu; ?></td>
              <td><?php echo $value->link; ?></td>
              <td><?php echo $value->weight; ?></td>
              <td><i class="<?php echo $value->icon; ?>"> (<?php echo $value->icon; ?>)</td>
              <td>
                <button class="btn btn-warning btn-sm" onclick="setMenu(<?php echo $value->id; ?>)" data-toggle='modal' data-target='#modal'>Edit</button>
                <button class="btn btn-danger btn-sm" onclick="deleteMenu(<?php echo $value->id; ?>)" data-toggle='modal' data-target='#modal'>Delete</button>
              </td>
            </tr>
            <?php
              }
            ?>
          </tbody>
          <tfoot>
            <tr>
              <th>#</th>
              <th>Module</th>
              <th>Menu</th>
              <th>Link</th>
              <th>Weight</th>
              <th>Icon</th>
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
  function addNewMenu() {

    $.ajax({
      type: 'POST', 
      url : '<?php echo base_url(); ?>admin/add_menu',
      success : function(result) {
        $('#modal').html(result);
      }
    });
  }
  function setMenu(id) { 

    $.ajax({
      type: 'POST', 
      url : '<?php echo base_url(); ?>admin/set_menu',
      data: 'id='+id,
      success : function(result) {
        $('#modal').html(result);
      }
    });
  }
  function deleteMenu(id) {

    $.ajax({
      type: 'POST', 
      url : '<?php echo base_url(); ?>admin/delete_menu',
      data: "id="+id,
      success : function(result) {
        $('#modal').html(result);
      }
    });
  }
</script>