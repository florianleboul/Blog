<div class="portfolio-modal" >
	<div class="modal-content">
	    <div class="container">
	        <div class="row">
	            <div class="col-lg-4 col-lg-offset-4">
	            	<form class="form-signin" action="/?action=login" method="post">
	            		<p class="text-danger"><?= $error ?? ""?></p>
						<img class="mb-4" src="/Signin Template for Bootstrap_files/bootstrap-solid.svg" alt="" width="72" height="72">
						<h1 class="h3 mb-3 font-weight-normal">Authentification</h1>
						<label for="inputEmail" class="sr-only">Adresse mail</label>
						<input type="email" id="inputEmail" class="form-control" placeholder="Email address" required="" autofocus="" name="email">
						<label for="inputPassword" class="sr-only">Mot de passe</label>
						<input type="password" id="inputPassword" class="form-control" placeholder="Password" required="" name="password">
						<div class="checkbox mb-3">
						<label>
							<!-- <input type="checkbox" value="remember-me"> Remember me -->
						</label>
						</div>
						<button class="btn btn-lg btn-primary btn-block" type="submit">Se connecter</button>
						<p>Pas encore membre ? <a href="/?action=register">S'enregistrer</a></p>
				    </form>
	            </div>
	        </div>
	    </div>
	</div>
</div>