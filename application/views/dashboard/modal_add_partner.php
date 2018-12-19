<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Tambahkan Partner</h4>
    </div>
    <?php
    echo form_open_multipart(current_url(), "class='form-horizontal'");
	if (isset($partner)){
	 echo "<input name='bmd_id_vendor' type='hidden' value='$partner->bmd_id_vendor'>";
	}
	?>
	<div class="modal-body">
      <div class="form-group">
        <label class="col-sm-2 control-label">id</label>
        <div class="col-sm-10">
          <input class="form-control" name="bmd_id_vendor" id="bmd_id_vendor" readonly type="text" value="<?php echo isset($partner) ? $partner->bmd_id_vendor : ''?>">
        </div>
      </div> 
	  <div class="form-group">
        <label class="col-sm-2 control-label">Nama Partner</label>
        <div class="col-sm-10">
          <input class="form-control" name="bmd_nama_vendor" id="bmd_nama_vendor" placeholder="Nama Partner" type="text" value="<?php echo isset($partner) ? $partner->bmd_nama_vendor : ''?>">
        </div>
      </div>
	  
      <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Kode</label>
        <div class="col-sm-10">
          <input class="form-control" name="bmd_kode_vendor" id="bmd_kode_vendor" placeholder="Kode Vendor" type="name" value="<?php echo isset($partner) ? $partner->bmd_kode_vendor : ''?>">
        </div>
      </div>
	  
      <div class="form-group">
        <label  class="col-sm-2 control-label">Jenis</label>
		    <div class="col-sm-10">
				<select name="bmd_jenis" class="form-control" >
					<option  value="<?php echo isset($partner) ? $partner->bmd_jenis : 'Haji dan Umroh'?>">Haji dan Umroh</option>
					<option value="<?php echo isset($partner) ? $partner->bmd_jenis : 'Tour and Travel'?>">Tour and Travel</option>
					<option value="<?php echo isset($partner) ? $partner->bmd_jenis : 'Hotel and Resort'?>"> Hotel and Resort</option>
								</select>
			</div>
        </div>
		
		<div class="form-group">
        <label  class="col-sm-2 control-label">Status</label>
		    <div class="col-sm-10">
				<select name="bmd_vendor_aktif" class="form-control" >
					<option  value="<?php echo isset($partner) ? $partner->bmd_vendor_aktif : '1'?>" >1</option>
					<option value="<?php echo isset($partner) ? $partner->bmd_vendor_aktif  : '0'?>">0</option>
					
				</select>
			</div>
        </div>
  
	<div class="form-group">
	
	        <label for="name" class="col-sm-2 control-label">Logo</label>
        <div class="col-sm-10">
          <input name="bmd_url_logo" id="bmd_url_logo" placeholder="Url Logo" type="file" value="<?php echo isset($partner) ? $partner->bmd_url_logo : ''?>">
        </div>
	
      </div>
	  
	  
		<div class="form-group">
			<label for="name" class="col-sm-2 control-label">link  Promo</label>
			<div class="col-sm-10">
			<input class="form-control" name="bmd_url_promo" id="bmd_url_promo" placeholder="Url Promo" type="name" value="<?php echo isset($partner) ? $partner->bmd_url_promo : ''?>">
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
		</form>
  </div>
  <!-- /.modal-content -->
</div>

