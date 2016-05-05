<div class="module_main">
	<h3><?php $this->pr($this->title); ?></h3>
	<form action="/account" method="post">
		<label for="email">Email:</label>
		<input type="email" name="Email" id="email" value="<?php $this->pr($this->email); ?>" required><br>
		<label for="tz">Timezone:</label>
		<?php
			echo '<select name="Timezone" id="tz" required>';
			foreach($this->timezones as $tz) {
				if ($tz == $this->user_tz)
					echo '<option value="'.$this->pr($tz, null, false).'" selected>'.$this->pr($tz, null, false).'</option>';
				else
					echo '<option value="'.$this->pr($tz, null, false).'">'.$this->pr($tz, null, false).'</option>';
			}
			echo '</select>';
		?>
		<p><input type="submit" class="loginbutton" value="Update"></p>
	</form>
	<a href="/account/password">Click here to change your password.</a>
</div>