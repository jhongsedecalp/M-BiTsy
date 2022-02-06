<p class='text-center'><b>Add user</b></p>
<div class='ttform'>
<div class='text-center'>

<form method=post action='<?php echo URLROOT; ?>/adminuser/addeduserok'>
	<label for="name">Username</label>
	<input id="name" type="text" class="form-control" name="username" minlength="3" maxlength="25" required autofocus>

	<label for="name">Password</label>
	<input id="name" type="password" class="form-control" name="password" minlength="3" maxlength="25" required autofocus>

	<label for="name">Re-type password </label>
	<input id="name" type="password" class="form-control" name="password2" minlength="3" maxlength="25" required autofocus>

	<label for="name">E-mail</label>
	<input id="name" type="text" class="form-control" name="email" minlength="3" required autofocus>

	<button type="submit" class="btn ttbtn  btn-sm">Okay</button>

</form>
</div>
</div>