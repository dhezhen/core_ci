<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Conformation</h4>
    </div>
    <?php
    echo form_open(current_url(), "class='form-horizontal'");
    ?>
    <div class="modal-body">
      <input type="hidden" name="id" id="id" value="<?php echo $id; ?>"/>
      <p>Are you sure to do a delete?</p>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
      <input type="submit" name="submit" class="btn btn-danger" value="Delete"/>
    </div>
    <?php
    echo form_close();
    ?>
  </div>
  <!-- /.modal-content -->
</div>