<!DOCTYPE html>
<html>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="theme-color" content="#222222">
	<title><?php $this->pr($this->title); ?></title>
	<link rel="shortcut icon" href="/favicon.ico?v=2">
	<link rel="stylesheet" href="/main.css" type="text/css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans" type="text/css">
	<?php if ($this->isEmpty($this->username)) { ?>
	<link rel="stylesheet" href="/lib/popup/style.css" type="text/css">
	<?php } ?>
	<?php if ($this->slider) { ?>
	<link rel="stylesheet" href="/lib/slider/js-image-slider.css" type="text/css">
	<script src="/lib/slider/js-image-slider.js" type="text/javascript"></script>
	<?php } ?>
	<?php if (($this->isEmpty($this->username)) || ($this->recaptcha)) { ?>
	<script src="https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit" async defer></script>
	<script type="text/javascript">
		var CaptchaCallback = function(){
			<?php if ($this->attempts) { ?>
			grecaptcha.render('recaptchaLogin', {'sitekey' : '<?php $this->pr($this->recaptchakey); ?>'});
			<?php } ?>
			grecaptcha.render('recaptchaRegister', {'sitekey' : '<?php $this->pr($this->recaptchakey); ?>'});
		};
	</script>
	<?php } ?>
	<?php if ($this->msg_box) { ?>
		<script type="text/javascript">alert('<?php $this->pr($this->msg_box); ?>')</script>
	<?php } ?>
<body>
<?php if ($this->isEmpty($this->username)) { ?>
	<div class="popup-wrapper" id="loginpopup">
		<div class="login-form">
			<form action="/login" method="post">
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Username" name="Username" required>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" placeholder="Password" name="Password" required>
				</div>
				<?php if ($this->attempts) { ?>
				<div class="form-group">
					<div id="recaptchaLogin"></div>
				</div>
				<?php } ?>
				<input type="hidden" name="path" value="<?php echo strtok($_SERVER["REQUEST_URI"],'?'); ?>">
				<?php if (!$this->isEmpty($this->poperror)) { ?>
				<span class="alert"><?php $this->pr($this->poperror, 300); ?></span>
				<?php } ?>
				<a class="link" href="#forgotpopup">Lost your password?</a>
				<input type="submit" class="log-btn loginbutton" value="Log In">
				<a class="popup-close" href="#closed">X</a>
			</form>
		</div>
	</div>
	<div class="popup-wrapper" id="registerpopup">
		<div class="login-form">
			<form action="/register" method="post">
				<div class="form-group ">
					<input type="text" class="form-control" placeholder="Username" name="Username" required>
				</div>
				<div class="form-group">
					<input type="email" class="form-control" placeholder="Email" name="Email" required>
				</div>
				<div class="form-group">
					Note: A password will be e-mailed to you.
				</div>
				<div class="form-group">
					<div id="recaptchaRegister"></div>
				</div>
				<input type="hidden" name="path" value="<?php echo strtok($_SERVER["REQUEST_URI"],'?'); ?>">
				<?php if (!$this->isEmpty($this->poperror)) { ?>
				<span class="alert"><?php $this->pr($this->poperror, 300); ?></span>
				<?php } ?>
				<input type="submit" class="log-btn loginbutton" value="Register">
			</form>
			<a class="popup-close" href="#closed">X</a>
		</div>
	</div>
	<div class="popup-wrapper" id="forgotpopup">
		<div class="login-form">
			<p>Sorry, not implemented yet.</p>
			<div class="form-group">
				<input type="email" class="form-control" placeholder="Email" name="Email" required>
			</div>
			<button type="button" class="log-btn loginbutton">Nope</button>
			<a class="popup-close" href="#closed">X</a>
		</div>
	</div>
<?php } ?>
	<div id="header">
		<div class="logoheader"> 
			<h5 id="logo">
				<a href="/"><img alt="Logo" src="/images/logo.png" style="height: 170px;"></a>
			</h5>
		</div>
		<div class="menuheader">
			<div class="search-container">
				<form action="/search" method="get">
					<input class="search" id="searchbox" type="search" name="q" placeholder="Search">
					<a onclick="document.getElementById('searchbox').focus();" class="searchbutton"><div style="-webkit-transform: rotate(-45deg); -moz-transform: rotate(-45deg); -o-transform: rotate(-45deg); -ms-transform: rotate(-45deg);">&#9906;</div></a>
				</form>
			</div>
			<?php if ($this->isEmpty($this->username)) { ?>
			<a href="#loginpopup" class="loginbutton">Log In</a>
			<a href="#registerpopup" class="loginbutton">Register</a>
			<?php } else { ?>
			<a href="/account" class="loginbutton"><?php $this->pr($this->username); ?></a>
			<a href="/logout" class="loginbutton">Log Out</a>
			<?php } ?>
		</div>
		<div id="topmenu">
			<ul id="nav_top">
				<li>
					<a href="/">Home</a>
				</li>
				<li>
					<a href="/cups">Cups</a>
				</li>
				<li>
					<a href="/donate">Donate</a>
				</li>
				<li class="last">
					<a href="/about-us">About us</a>
				</li>
			</ul>
		</div>
	</div>
	<div id="content">
		<div class="mpage">