<div class="module_main">
	<h3><?php $this->pr($this->title); ?></h3>
	<form action="/account" method="post">
		<?php if($this->oldreq) { ?>
		<label for="oldpassword">Old password:</label>
		<input type="password" name="OldPassword" id="oldpassword" required><br>
		<?php } ?>
		<label for="newpassword">New password:</label>
		<input type="password" name="NewPassword" id="newpassword" required><br>
		<p><input type="submit" class="loginbutton" value="Update"></p>
	</form>
</div>