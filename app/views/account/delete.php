<div class="module_main">
	<h3><?php $this->pr($this->title); ?></h3>
	<form action="/account/delete" method="post">
		<label for="password">Password:</label>
		<input type="password" name="Password" id="password" required><br>
		<p>Warning: Your comments will still be existing but without your username. <b>The deletion cannot be undone.</b></p>
		<a href="/account" class="loginbutton">Cancel</a><input type="submit" class="loginbutton delete" value="Delete account">
	</form>
</div>