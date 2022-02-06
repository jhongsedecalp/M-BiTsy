<div class="ttform">
    <form method="post" action="<?php echo URLROOT; ?>/moviedatabase/submit" autocomplete="off">

    <div class="text-center">
    <label for="inputsearch" class="col-form-label"><?php echo Lang::T("Search TMDB (The Movie Database)"); ?></label>
       <input id="inputsearch" type="text" class="form-control" name="inputsearch" minlength="3" maxlength="40" required autofocus><br>
        </div>

    <div class="text-center">
    <button id="input" name="input"type="submit" class="btn ttbtn" value="movie"><?php echo Lang::T("Search Movie"); ?></button>
	<button id="input" name="input"type="submit" class="btn ttbtn" value="show"><?php echo Lang::T("Search Show"); ?></button>
	<button id="input" name="input" type="submit" class="btn ttbtn" value="person"><?php echo Lang::T("Search Person"); ?></button>
	</div>

    </form>
</div><br>

<div class="ttform">
<div class="text-center"> <?php
        // Full Movie Info
        echo '<a id="movieInfo"><h3>Full Movie Info</h3></a>';
        $movie = $data['tmdb']->getMovie($data['id']);
        echo '<b>' . $movie->getTitle() . '</b><br>';
        echo 'ID<br>' . $movie->getID() . '<br>';
        echo 'Tagline<br>' . $movie->getTagline() . '<br>';
        echo 'cast<br>';
        echo 'Trailer<br> <a href="https://www.youtube.com/watch?v=' . $movie->getTrailer() . '">link</a><br>';
        echo '<img src="' . $data['tmdb']->getImageURL('w185') . $movie->getPoster() . '"/><br>';

?>
</div>
</div>