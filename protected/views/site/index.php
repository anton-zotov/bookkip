<div class="half-screen">
	<div class="vertical-middle">
		<div class="description-container">
			<h1>Bookkip</h1>
			<h2>Your personal book list</h2>
			<ul id="main-list">
				<li>Add a book you've read</li>
				<li>Describe you thougts about the book</li>
				<li>Put some tags</li>
				<li>Keep track of books you want to read</li>
			</ul>
		</div>
	</div>
</div>
<div class="half-screen">
	<div class="vertical-middle">
		<ul class="tabs">
			<li <?= $method != 'login' ? 'class="active"' : ''; ?> target="register-form">Register</li>
			<li <?= $method == 'login' ? 'class="active"' : ''; ?> target="login-form">Log in</li>
		</ul>
		<div id="form-holder">
			<div class="social-button-holder">
				<div class="fb-login-button" data-max-rows="1" data-size="large" data-show-faces="false" scope="email" data-auto-logout-link="false" onlogin="onFBLogin()"></div>
				<div id="vk-login-button" onclick="VK.Auth.login(onVKLogin);"></div>
			</div>
			<form id='register-form' method="POST" action='/site/register'>
				<? if ($message && $method == 'register'): ?>
					<div class="error-description"><?=$message;?></div>
				<? endif; ?>
				<input type="text" placeholder="Your name" name="name" value="<?= isset($fields['name']) ? $fields['name'] : ''; ?>"/>
				<input type="text" placeholder="Email" name="email" value="<?= isset($fields['email']) ? $fields['email'] : ''; ?>"/>
				<input type="password" placeholder="Password" name="password"/>
				<div class="button-holder">
					<button type="submit" class="btn btn-blue" id="reg-submit">Start using</button>
				</div>
			</form>
			<form id='login-form' method="POST" action='/site/login'>
				<? if ($message && $method == 'login'): ?>
					<div class="error-description"><?=$message;?></div>
				<? endif; ?>
				<input type="text" placeholder="Email" name="email" value="<?= isset($fields['email']) ? $fields['email'] : ''; ?>"/>
				<input type="password" placeholder="Password" name="password"/>
				<div class="button-holder">
					<button type="submit" class="btn btn-blue" id="reg-submit">Log in</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div id="background"><div></div></div>
<div id="vk_api_transport" style="position: absolute; top: -10000px;"></div>

<!-- Facebook -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.3&appId=1461730330812482";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>