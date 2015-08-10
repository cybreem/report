<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Login Page</title>
		<meta name="msapplication-TileColor" content="#5bc0de" />
		<meta name="msapplication-TileImage" content="<?php echo config_item('aseets'); ?>img/metis-tile.png" />
		<link rel="stylesheet" href="<?php echo config_item('assets'); ?>css/bootstrap.css">
		<link rel="stylesheet" href="<?php echo config_item('assets'); ?>css/animate.css">
		<link rel="stylesheet" href="<?php echo config_item('assets'); ?>css/main.css">
	</head>
	<body class="login">
		<div class="form-signin">
			<div class="text-center">
				<img src="<?php echo config_item('assets'); ?>/img/logo.png" alt="Metis Logo">
			</div>
			<hr>
			<div class="tab-content">
				<?php if($this->session->flashdata('alert')) { ?>
				    <p class='text-danger text-center'><?php echo $this->session->flashdata('alert'); ?></p>
				<?php } else { ?>
				    <p class='text-muted text-center'>Enter your NIP and password</p>
				<?php } ?>
				<div id="login" class="tab-pane active">
					<?php echo form_open('auth/login'); ?>
    					<input type="text" name="nip" placeholder="NIP" class="form-control top">
    					<input type="password" name="password" placeholder="Password" class="form-control bottom">
    					<button class="btn btn-lg btn-primary btn-block" type="submit">
    						Sign in
    					</button>
					</form>
				</div>
			</div>
			<hr>
		</div>
		<script src="<?php echo config_item('assets'); ?>js/jquery.min.js"></script>
		<script src="<?php echo config_item('assets'); ?>js/bootstrap.min.js"></script>
	</body>
</html>