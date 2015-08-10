<div class="box">
	<header>
		<h5><i class="fa fa-user"></i> Member Profile</h5>
	</header>
	<div class="body">
		<div class="row">
			<form class="form-horizontal" id="xx" method="post">
				<div class="col-lg-6">
					<div class="form-group">
						<label class="control-label col-lg-2">NIP</label>
						<div class="col-lg-8">
							<input type="text" name="nip" class="form-control" value="<?php echo set_value('nip', $nip); ?>" <?php echo (validation_errors() != false) ? '':'disabled';?>>
							<?php echo form_error('nip'); ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-2">Name</label>
						<div class="col-lg-8">
							<input type="text" name="name" class="form-control" value="<?php echo set_value('name', $name); ?>" <?php echo (validation_errors() != false) ? '':'disabled';?>>
							<?php echo form_error('name'); ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-2">Email</label>
						<div class="col-lg-8">
							<input type="text" name="email" class="form-control" value="<?php echo set_value('email', $email); ?>" <?php echo (validation_errors() != false) ? '':'disabled';?>>
							<?php echo form_error('email'); ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-2">Avaya Number</label>
						<div class="col-lg-8">
							<input type="text" name="avaya" class="form-control" value="<?php echo set_value('avaya', $avaya); ?>" <?php echo (validation_errors() != false) ? '':'disabled';?>>
							<?php echo form_error('avaya'); ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-2">Phone</label>
						<div class="col-lg-8">
							<input type="text" name="phone" class="form-control" value="<?php echo set_value('phone', $phone); ?>" <?php echo (validation_errors() != false) ? '':'disabled';?>>
							<?php echo form_error('phone'); ?>
						</div>
					</div>
					<div class="form-group">
						
						<label class="control-label col-lg-2">Password</label>
						<div class="col-lg-8">
							<button class="btn btn-warning" id="changePSWD" type="button">Change Password</button>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<label class="control-label col-lg-2">Address</label>
						<div class="col-lg-8">
							<textarea name="address" class="form-control" <?php echo (validation_errors() != false) ? '':'disabled';?>><?php echo set_value('address', $address); ?></textarea>
							<?php echo form_error('address'); ?>
						</div>
						
					</div>
					<div class="form-group">
						<label class="control-label col-lg-2">Division</label>
						<div class="col-lg-8">
							<?php echo $division?>
						</div>
						
					</div>
					<div class="form-group">
						<label class="control-label col-lg-2">Level</label>
						<div class="col-lg-8">
							<?php echo $level?>
						</div>
						
					</div>
					<div class="form-group">
						<label class="control-label col-lg-2">&nbsp;</label>
						<div class="col-lg-8">
							<div class="btn-group pull-left" id="dd">
								<button class="btn btn-primary" type="button" id="btn-cancel" <?php echo (validation_errors() != false) ? '':'disabled';?>>Cancel</button>
								<button class="btn btn-primary" type="button" id="btn-update">Update</button>
							</div>                            
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>