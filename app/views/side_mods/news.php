<div class="module_main side">
	<h3>News</h3>
	<?php
		if (is_array($this->side_news)) {
			foreach ($this->side_news as $p) {
				echo '<a href="/p/' . $this->pr($p['linktitle'], null, false) . '">» ' . $this->pr($p['title'], null, false) . '</a>';
			}
		} else {
			echo "<p>None</p>";
		}
	?>
</div>