<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Default Modal</h4>
    </div>
    <?php
    echo form_open(current_url(), "class='form-horizontal'");
    echo isset($menu) ? "<input type='hidden' name='id' value='$menu->id'/>" : "";
    ?>
    <div class="modal-body">
      <div class="form-group">
        <label for="module" class="col-sm-2 control-label">Module</label>
        <div class="col-sm-10">
          <input class="form-control" name="module" id="module" placeholder="Module" type="text" value="<?php echo isset($menu) ? $menu->module : ''?>">
        </div>
      </div>
      <div class="form-group">
        <label for="menu" class="col-sm-2 control-label">Menu</label>
        <div class="col-sm-10">
          <input class="form-control" name="menu" id="menu" placeholder="Menu" type="text" value="<?php echo isset($menu) ? $menu->menu : ''?>">
        </div>
      </div>
      <div class="form-group">
        <label for="link" class="col-sm-2 control-label">Link</label>
        <div class="col-sm-10">
          <input class="form-control" name="link" id="link" placeholder="Menu" type="text" value="<?php echo isset($menu) ? $menu->link : ''?>">
        </div>
      </div>
      <div class="form-group">
        <label for="weight" class="col-sm-2 control-label">Weight</label>
        <div class="col-sm-10">
          <input class="form-control" name="weight" id="weight" placeholder="Weight" type="text" value="<?php echo isset($menu) ? $menu->weight : ''?>">
        </div>
      </div>
      <div class="form-group">
        <label for="icon" class="col-sm-2 control-label">Icon</label>
        <div class="col-sm-10">
          <input class="form-control" name="icon" id="icon" placeholder="Menu" type="Icon" value="<?php echo isset($menu) ? $menu->icon : ''?>">
        </div>
      </div>
      <div class="form-group">
        <label for="parent_d" class="col-sm-2 control-label">Parent</label>
        <div class="col-sm-10">
          <select class="form-control" name="parent_id" id="parent_id">
            <option value="0">Is parent</option>
            <?php
            foreach($parent_menu as $value) {
              $isSelect = (isset($menu) && ($menu->parent_id == $value->id)) ? "selected" : "";
              echo "<option value='$value->id' $isSelect>$value->module - $value->menu</option>";
            }
            ?>
          </select>
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