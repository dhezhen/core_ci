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
    <input type="hidden" id="menu_id" name="menu_id" value="<?php echo $menu_id; ?>"/>
    <div class="modal-body">
      <table class="table table-responsive">
        <thead>
          <tr>
            <th>Name</th>
            <th>Access</th>
            <th>is Admin</th>
          </tr>
        </thead>
        <tbody>
          <?php
          echo json_encode($access);
          foreach($groups as $value) {
            // $isCheck = (in_array($value->id, $checked)) ? " checked" : "";
            $isAccess = (isset($access[$value->id])) ? " checked" : false;
            // $isAdmin = "";
            $isAdmin = ($isAccess && $access[$value->id][0]["is_admin"]) ? " checked" : false;
          ?>
          <tr>
            <td><?php echo $value->name; ?></td>
            <td><input type="checkbox" class="menu" name="group[]" value="<?php echo $value->id; ?>"<?php echo $isAccess; ?>></td>
            <td><input type="checkbox" class="menu" name="admin[]" value="<?php echo $value->id; ?>"<?php echo $isAdmin; ?>></td>
          </tr>
          <?php
          }
          ?>
        </tbody>
        <tfoot>
          <tr>
            <th>Name</th>
            <th>Access</th>
            <th>is Admin</th>
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