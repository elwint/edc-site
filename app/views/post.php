<div class="module_main">
	<h3><?php $this->pr($this->title) ?></h3>
	<span class="date_title">Posted on: <?php $this->pr($this->date); if ($this->reg_datetime) echo "<br>Registrations open until: ".$this->pr($this->reg_datetime, null, false); ?></span>
	<?php
		if ($this->reg_datetime) {
			if ($this->reg_end) {
				echo '<a href="#" class="loginbutton signlight">Registrations closed</a>';
			} elseif ($this->participated) {
				echo '<form action="' . strtok($_SERVER["REQUEST_URI"], '?') . '" method="post">
						<input type="hidden" name="participate" value="false">
						<input type="submit" class="loginbutton signlight" value="Sign out this cup">
					</form>';
			} else {
				echo '<form action="' . strtok($_SERVER["REQUEST_URI"], '?') . '" method="post">
						<input type="hidden" name="participate" value="true">
						<input type="submit" class="loginbutton sign" value="Click to Participate!">
					</form>';
			}
		}
	?>
	<p><?php echo $this->content ?></p>
</div>
<h3>Comments</h3>
Sorry, not implemented yet.