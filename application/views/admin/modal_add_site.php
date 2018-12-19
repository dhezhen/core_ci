<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Default Modal</h4>
    </div>
    <?php
    echo form_open(current_url(), "class='form-horizontal'");
    $isEdit = isset($site) ? " readonly" : "";
    ?>
    <div class="modal-body">
      <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Name</label>
        <div class="col-sm-10">
          <input class="form-control" name="name" id="name" placeholder="Name" type="text" value="<?php echo isset($site) ? $site->name : ''?>"<?php echo $isEdit; ?>>
        </div>
      </div>
      <div class="form-group">
        <label for="value" class="col-sm-2 control-label">Value</label>
        <div class="col-sm-10">
          <input class="form-control" name="value" id="value" placeholder="Value" type="text" value="<?php echo isset($site) ? $site->value : ''?>">
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
      <input type="submit" name="submit" class="btn btn-primary" value="Save"/>
    </div>
    <?php
    echo form_close();
    ?>
  </div>
  <!-- /.modal-content -->
</div>