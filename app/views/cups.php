<div id="left">
	<?php if (is_array($this->cups)) { foreach ($this->cups as $cup) { ?>
	<div class="module_cup">
		<div class="wrapper">
			<a href="/p/<?php $this->pr($cup['linktitle']) ?>"><img src="<?php $this->pr($cup['sliderimage']) ?>" alt="<?php $this->pr($cup['title']) ?>"></a>
			<div class="desc"><p><?php $this->pr($cup['title']) ?></p></div>
		</div>
	</div>
	<?php }} else { ?>
	<div class="module_main">
		<p>No cups found =/</p>
	</div>
	<?php } ?>
</div>
<div id="right">
	<?php
		$this->makeMod('side_mods/news');
		$this->makeMod('side_mods/matches');
		$this->makeMod('side_mods/results');
		$this->makeMod('side_mods/server_banner');
	?>
</div>