<div class="module_main side">
	<h3>Results</h3>
	<?php
	if (is_array($this->side_results)) {
		foreach ($this->side_results as $p) {
			echo '<a href="/results/' . $this->pr($p['linktitle'], null, false) . '">' . $this->pr($p['title'], null, false) . '</a>';
		}
	} else {
		echo "<p>None</p>";
	}
	?>
</div>