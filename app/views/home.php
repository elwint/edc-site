<div id="left">
	<div class="module_slider">
		<div id="sliderFrame">
			<div id="slider">
				<a href="#"><img src="/files/sliderimages/track_mp1.jpg" alt="First test"></a>
				<a href="#"><img src="/files/sliderimages/wallpaper_grass.jpg" alt="Second test"></a>
				<a href="#"><img src="/files/sliderimages/fb_cover.jpg" alt="Third test"></a>
			</div>
		</div>
	</div>
	<h3>Latest Post</h3>
	<div class="module_main">
		<h3>Welcome to our new website!</h3>
		<span class="date_title">8-May-2015</span>
		<p>We don't have any sponsors (yet).<br>
			If you are interested in sponsoring, you can <a href="#">contact us here</a>.
		</p>
	</div>
</div>
<div id="right">
	<?php
		$this->makeMod('side_mods/news');
		$this->makeMod('side_mods/matches');
		$this->makeMod('side_mods/results');
		$this->makeMod('side_mods/banners');
	?>
</div>