<div class="module_main">
	<h3><?php $this->pr($this->title); ?></h3>
	<form action="/account" method="post">
		<label for="tz">Timezone:</label>
		<?php
			echo '<select name="Timezone" id="tz" required>';
			foreach($this->timezones as $tz) {
				if ($tz == $this->user_tz)
					echo '<option value="'.$this->pr($tz, null, false).'" selected>'.$this->pr($tz, null, false).'</option>';
				else
					echo '<option value="'.$this->pr($tz, null, false).'">'.$this->pr($tz, null, false).'</option>';
			}
			echo '</select><br>';
		?>
		Email: <?php $this->pr($this->email); ?><br><br>
		Receive import cup email notifications from:<br>
		<?php
		if ($this->isEmpty($this->cups)) {
			echo "None";
		} else {
			foreach ($this->cups as $cup) {
				$extra = (ord($cup['notify']) == "1") ? "checked" : "";
				echo '<input type="checkbox" name="'.$this->pr($cup['id'], null, false).'" value="1" '.$extra.'> '.$this->pr($cup['title'], null, false).'<br>';
			}
		}
		?>
		<p><input type="submit" class="loginbutton" value="Update"></p>
	</form>
	<a href="/account/password">Click here to change your password</a><br>
	<span style="font-size: 10px;"><a href="/account/delete">Click here to delete your account permanently</a></span>
</div>