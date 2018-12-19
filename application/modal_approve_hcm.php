<div class="modal-header no-padding">
	<div class="table-header">
		Konfirmasi Approval
	</div>
</div>
<?php echo form_open(current_url(), 'class="form-horizontal"'); ?>
<input type="hidden" name="idPerdin" id="idPerdin" value="<?php echo $idPerdin; ?>"/>
<div class="modal-body">
	<div class='row-fluid'>
		<div class="span12">
			<?php
			if(is_array($anggaran) || !empty($anggaran)) {
			?>
			<div class='row-fluid'>
				<div class="span12">
					<div class="control-group">
						<label class="control-label">Anggaran Perdin</label>
						<div class="controls">
							<input type="text" id="anggaran" name="anggaran" value="Rp. <?php echo number_format($anggaran->nominal); ?>" readonly/>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Saldo Anggaran</label>
						<div class="controls">
							<input type="text" id="saldo" name="saldo" value="Rp. <?php echo number_format($anggaran->saldo); ?>" readonly/>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Pengajuan</label>
						<div class="controls">
							<input type="text" id="tgl_insert" name="tgl_insert" value="Rp. <?php echo number_format($pengajuan); ?>" readonly/>
						</div>
					</div>
					<?php
						$saldo = $anggaran->saldo - $pengajuan;
					?>
					<div class="control-group">
						<label class="control-label">Saldo</label>
						<div class="controls">
							<input type="text" id="tgl_insert" name="tgl_insert" value="Rp. <?php echo number_format($saldo); ?>" readonly/>
						</div>
					</div>
				</div>
			</div>
			<?php
			}
			else {
				echo "Anggaran perjalanan dinas untuk bulan sekarang belum disediakan, tetapi proses approval masih dapat dilakukan";
			}
			?>
		</div>
	</div>
</div>
<div class="modal-footer center">
	<input type="submit" class="btn btn-primary btn-small" name="submit" value="Approve"/>
	<button type="reset" data-dismiss="modal" class="btn btn-info btn-small">
		Cancel
	</button>
</div>
<?php echo form_close(); ?>