<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Default Modal</h4>
    </div>
    <?php
    echo form_open(current_url(), "class='form-horizontal' role='form'");
    ?>
    <input type="hidden" id="group_id" name="group_id" value="<?php echo $group_id; ?>"/>
    <div class="modal-body">
      <table class="table table-responsive">
        <thead>
          <tr>
            <th>Name</th>
            <th>Access</th>
          </tr>
          <tr>
            <th>
              Check All
            </th>
            <th>
              <input type="checkbox" id="check_all_user">
            </th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach($users as $value) {
            $isCheck = (in_array($value->user_id, $checked)) ? " checked" : "";
          ?>
          <tr>
            <td><?php echo $value->user_name; ?></td>
            <td><input type="checkbox" class="user" name="users[]" value="<?php echo $value->user_id; ?>"<?php echo $isCheck; ?>></td>
          </tr>
          <?php
          }
          ?>
        </tbody>
        <tfoot>
          <tr>
            <th>Name</th>
            <th>Insert</th>
          </tr></tfoot>
      </table>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
      <input type="submit" name="submit" class="btn btn-primary" value="Save"/>
    </div>
    <?php
    echo form_close();
    ?>
  </div>
</div>
<script>
  $("#check_all_user").click( function(){
    if($(this).is(':checked')) {
      $('.user').prop('checked', true);
    }
    else {
      $('.user').prop('checked', false);
    }
  });
</script>