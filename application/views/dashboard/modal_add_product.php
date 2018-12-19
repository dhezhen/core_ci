<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Add Partner</h4>
    </div>
    <?php
    echo form_open_multipart(current_url(), "class='form-horizontal'");
	//set id untuk parameter update
	if (isset($product)){
	 echo "<input name='bmd_id' type='hidden' value='$product->bmd_id'>";
	}
    ?>
    <div class="modal-body">
      <div class="form-group">
        <label class="col-sm-2 control-label">Nama Partner</label>
        <div class="col-sm-10">
          <input class="form-control" name="bmd_nama_vendor" id="bmd_nama_vendor" placeholder="Nama Partner" type="text" value="<?php echo isset($product) ? $product->bmd_nama_vendor : ''?>">
        </div>
      </div>
	  
    <div class="form-group">
        <label class="col-sm-2 control-label">Nama Paket</label>
        <div class="col-sm-10">
          <input class="form-control" name="bmd_nama_paket" id="bmd_nama_paket" placeholder="Nama Paket" type="text" value="<?php echo isset($product) ? $product->bmd_nama_paket : ''?>">
        </div>
      </div>
 <div class="form-group">
        <label class="col-sm-2 control-label">Deskripsi</label>
        <div class="col-sm-10">
          <input class="form-control" name="bmd_deskripsi" id="bmd_deskripsi" placeholder="Deskripsi" type="text" value="<?php echo isset($product) ? $product->bmd_deskripsi : ''?>">
        </div>
      </div>

	  <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Gambar Promo</label>
        <div class="col-sm-10">
          <input class="form-control" name="bmd_url_gambarpromo" id="bmd_url_gambarpromo" placeholder="Upload Gambar Promo" type="file" value="<?php echo isset($product) ? $product->bmd_url_gambarpromo : ''?>">
        </div>
      </div>  
	  <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Harga </label>
        <div class="col-sm-10">
          <input class="form-control" name="bmd_harga" id="bmd_harga" placeholder="harga" type="number_format" value="<?php echo isset($product) ? $product->bmd_harga : ''?>">
        </div>
      </div> 

	  <div class="form-group">
        <label for="name" class="col-sm-2 control-label">link paket</label>
        <div class="col-sm-10">
          <input class="form-control" name="bmd_url_paket" id="bmd_url_paket" placeholder="Nama Paket" type="name" value="<?php echo isset($product) ? $product->bmd_url_paket : ''?>">
        </div>
      </div>

	  <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Contact Person</label>
        <div class="col-sm-10">
          <input class="form-control" name="bmd_contact_person" id="bmd_contact_person" placeholder="Contact Person" type="tel" value="<?php echo isset($product) ? $product->bmd_contact_person : ''?>">
        </div>
      </div>
			
		<div class="form-group">
        <label  class="col-sm-2 control-label">Status</label>
		    <div class="col-sm-10">
				<select name="bmd_aktif" class="form-control" >
					<option  value="<?php echo isset($product) ? $product->bmd_aktif : '1'?>">1</option>
					<option value="<?php echo isset($product) ? $product->bmd_aktif  : '0'?>">0</option>
					
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