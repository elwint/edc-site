<div class="module_main side">
	<h3>Upcoming Matches</h3>
	<?php
	if (!$this->isEmpty($this->side_matches)) {
		foreach ($this->side_matches as $date => $matches) {
			echo '<p><b>'.$this->pr($date, null, false).'</b><br>';
			foreach ($matches as $p) {
				echo "<span title='You can change the timezone in your account settings'>".$this->pr($p['time'], null, false).':</span> <a href="/p/' . $this->pr($p['linktitle'], null, false) . '" style="display:inline;">' . $this->pr($p['title'], null, false) . '</a><br>';
			}
			echo '</p>';
		}
	} else {
		echo "<p>None</p>";
	}
	?>
</div>