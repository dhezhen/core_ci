<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Tambahkan Advertiser</h4>
    </div>
    <?php
    echo form_open_multipart(current_url(), "class='form-horizontal'");
	if (isset($advertiser)){
	 echo "<input name='bmad_id' type='hidden' value='$advertiser->bmad_id'>";
	}
	?>
	<div class="modal-body">
 	  <div class="form-group">
        <label class="col-sm-2 control-label">Advertiser</label>
        <div class="col-sm-10">
          <input class="form-control" name="bmad_name" id="bmad_name" placeholder="Nama Advertiser" type="text" value="<?php echo isset($advertiser) ? $advertiser->bmad_name : ''?>">
        </div>
      </div>
	  
      <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Tanggal Mulai </label>
        <div class="col-sm-10">
          <input class="form-control" name="bmad_date_start" id="bmad_date_start" placeholder="Tanggal Mulai" type="date" value="<?php echo isset($advertiser) ? $advertiser->bmad_date_start : ''?>">
        </div>
      </div>
	    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Tanggal Berakhir </label>
			<div class="col-sm-10">
			  <input class="form-control" name="bmad_date_end" id="bmad_date_end" placeholder="Tanggal Berakhir" type="date" value="<?php echo isset($advertiser) ? $advertiser->bmad_date_end : ''?>">
			</div>
      </div>	  

	  <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Gambar Iklan</label>
			<div class="col-sm-10">
			  <input class="form-control" name="bmad_image" id="bmad_image" placeholder="" type="file" value="<?php echo isset($advertiser) ? $advertiser->bmad_image : ''?>">
			</div>
      </div>
	  
	   <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Harga</label>
			<div class="col-sm-10">
			  <input class="form-control" name="bmad_harga" id="bmad_harga" placeholder="" type="text" value="<?php echo isset($advertiser) ? $advertiser->bmad_harga : ''?>">
			</div>
      </div>  
	  <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Link</label>
			<div class="col-sm-10">
			  <input class="form-control" name="bmad_link" id="bmad_link" placeholder="link" type="text" value="<?php echo isset($advertiser) ? $advertiser->bmad_link : ''?>">
			</div>
      </div>
		
		<div class="form-group">
        <label  class="col-sm-2 control-label">Status</label>
		    <div class="col-sm-10">
				<select name="bmad_status" class="form-control" >
					<option  value="<?php echo isset($advertiser) ? $advertiser->bmad_status : 'aktif'?>" >Aktif</option>
					<option value="<?php echo isset($advertiser) ? $advertiser->bmad_status  : 'tidak aktif'?>">Tidak Aktif</option>
					
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
		</form>
  </div>
  <!-- /.modal-content -->
</div>

