<link rel="stylesheet" href="<?php echo base_url(); ?>assets/adminlte/plugins/datatables/dataTables.bootstrap.css">
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">List</h3>
        <button class="btn btn-primary btn pull-right" onclick="addGroup()" data-toggle='modal' data-target='#modal'>Add</button>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="table" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
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
              <td><?php echo $value->name; ?></td>
              <td>
                <button class="btn btn-warning btn-sm" onclick="setUser(<?php echo $value->id; ?>)" data-toggle='modal' data-target='#modal'>Set User</button>
                <button class="btn btn-danger btn-sm" onclick="deleteGroup(<?php echo $value->id; ?>)" data-toggle='modal' data-target='#modal'>Delete</button>
              </td>
            </tr>
            <?php
              }
            ?>
          </tbody>
          <tfoot>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Action</th>
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
	function addGroup() {
		$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>admin/add_group",
			success : function(result) {
				$("#modal").html(result);
			}
		})
	}
	function setUser(id) {
		// id = id group
		$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>admin/set_user_group",
			data: "id="+id,
			success : function(result) {
				$("#modal").html(result);
			}
		});
	}
</script>