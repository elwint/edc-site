<!DOCTYPE html>
<html>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="theme-color" content="#222222">
	<title><?php echo htmlspecialchars($this->title, ENT_QUOTES); ?></title>
	<link rel="shortcut icon" href="/favicon.ico?v=2">
	<link rel="stylesheet" href="/main.css" type="text/css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans" type="text/css">
	<link rel="stylesheet" href="/lib/slider/js-image-slider.css" type="text/css">
	<link rel="stylesheet" href="/lib/popup/style.css" type="text/css">
	<script src="/lib/slider/js-image-slider.js" type="text/javascript"></script>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<body>
	<div class="popup-wrapper" id="loginpopup">
		<div class="login-form">
			<div class="form-group log-status">
				<input type="text" class="form-control" placeholder="Username " id="UserName" required>
			</div>
			<div class="form-group log-status">
				<input type="password" class="form-control" placeholder="Password" id="Password" required>
			</div>
			<span class="alert">Invalid Credentials</span>
			<a class="link" href="#forgotpopup">Lost your password?</a>
			<button type="button" class="log-btn loginbutton">Log In</button>
			<a class="popup-close" href="#closed">X</a>
		</div>
	</div>
	<div class="popup-wrapper" id="registerpopup">
		<div class="login-form">
			<div class="form-group ">
				<input type="text" class="form-control" placeholder="Username " id="UserName" required>
			</div>
			<div class="form-group log-status">
				<input type="password" class="form-control" placeholder="Password" id="Password" required>
			</div>
			<div class="form-group log-status">
				<input type="text" class="form-control" placeholder="Email" id="Email" required>
			</div>
			<div class="form-group">
				<div class="g-recaptcha" data-sitekey="your_site_key"></div>
			</div>
			<span class="alert">Invalid [Input]</span>
			<button type="button" class="log-btn loginbutton">Register</button>
			<a class="popup-close" href="#closed">X</a>
		</div>
	</div>
	<div class="popup-wrapper" id="forgotpopup">
		<div class="login-form">
			<p>sdgkg</p>
			<div class="form-group log-status">
				<input type="email" class="form-control" placeholder="Email" id="Email" required>
			</div>
			<button type="button" class="log-btn loginbutton">R.I.P.</button>
			<a class="popup-close" href="#closed">X</a>
		</div>
	</div>
	<div id="header">
		<div class="logoheader"> 
			<h5 id="logo">
				<a href="/"><img alt="Logo" src="/images/logo.png" style="height: 170px;"></a>
			</h5>
		</div>
		<div class="menuheader">
			<div class="search-container">
				<input class="search" id="searchbox" type="search" placeholder="Search">
				<a onclick="document.getElementById('searchbox').focus();" class="searchbutton">🔍</a>
			</div>
			<a href="#loginpopup" class="loginbutton">Log In</a>
			<a href="#registerpopup" class="loginbutton">Register</a>
		</div>
		<div id="topmenu">
			<ul id="nav_top">
				<li>
					<a href="/">Home</a>
				</li>
				<li>
					<a href="#">Cups</a>
				</li>
				<li>
					<a href="#">Donate</a>
				</li>
				<li class="last">
					<a href="/about-us">About us</a>
				</li>
			</ul>
		</div>
	</div>
	<div id="content">
		<div class="mpage">