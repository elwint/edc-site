<div id="left">
	<?php if (!$this->isEmpty($this->sliders)) { ?>
	<div class="module_slider">
		<div id="sliderFrame">
			<div id="slider">
				<?php
					foreach($this->sliders as $slider) {
						echo '<a href="/p/'.$this->pr($slider['linktitle'],null,false).'"><img src="'.$this->pr($slider['sliderimage'],null,false).'" alt="'.$this->pr($slider['title'],null,false).'"></a>';
					}
				?>
			</div>
		</div>
	</div>
	<h3>Latest Post</h3>
	<?php } ?>
	<div class="module_main">
		<h3><?php $this->pr($this->post_title) ?></h3>
		<span class="date_title">Posted on: <?php $this->pr($this->post_date) ?></span>
		<p><?php echo $this->post_content ?></p>
	</div>
</div>
<div id="right">
	<?php
		$this->makeMod('side_mods/news');
		$this->makeMod('side_mods/matches');
		$this->makeMod('side_mods/results');
		$this->makeMod('side_mods/server_banner');
	?>
</div>