<!DOCTYPE html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title>Internal Server Error</title>
	<link rel="shortcut icon" href="/favicon.ico?v=2">
	<style>
		body { text-align: center; padding-top: 150px; }
		h1 { font-size: 50px; }
		body { font: 20px Helvetica, sans-serif; color: #333; }
		article { display: block; text-align: left; width: 650px; margin: 0 auto; }
		a { color: #dc8100; text-decoration: none; }
		a:hover { color: #333; text-decoration: none; }
	</style>
</head>
<body>
	<article>
		<h1>Whoops! Looks like something went wrong.</h1>
		<div>
			<p>A server error occurred. Please go to our <a href="https://www.facebook.com/edcuporganizing">Facebook page</a> or send us an <a href="mailto:edcuporganizing@gmail.com">email</a> to inform us of the time the error occurred, and anything you might have done that may caused the error.</p>
			<p>&mdash; EDCuporganizing</p>
		</div>
	</article>
	<?php
		if (DEVELOPER_MODE == "1") {
			echo "<span style='font-size: 14px;'><br><br><b>Fatal error:</b> {$this->error} (class '<b>{$this->class}</b>' function '<b>{$this->function}</b>').</span>";
		}
	?>
	<p style="position: fixed; bottom: 0; width:100%; text-align: center; font-size: 10px;">Copyright &#169; 2014-<script>document.write(new Date().getFullYear())</script> EDCuporganizing</p>
</body>
