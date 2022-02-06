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

        // Full Person Info
        echo '<a id="personInfo"><h3>Full Person Info</h3></a>';
        $person = $data['tmdb']->getPerson($data['id']);
        echo '<b>' . $person->getName() . '</b><br>';
        echo 'ID<br>' . $person->getID() . '</br>';
        echo 'Birthday<br>' . $person->getBirthday() . '</br>';
        echo 'Popularity<br>' . $person->getPopularity() . '</br>';
        echo '<img src="' . $data['tmdb']->getImageURL('w185') . $person->getProfile() . '"/>';


        // Get the movie roles
        echo '<a id="personRoles"><h3>Movie Roles</h3></a>';
        $movieRoles = $person->getMovieRoles();
        echo '<b>' . $person->getName() . '</b> - Roles in <b>Movies</b>: <br>';
        foreach ($movieRoles as $movieRole) {
            echo $movieRole->getCharacter() . ' in <a href="'.URLROOT.'/moviedatabase/movies?id=' . $movieRole->getMovieID() . '">' . $movieRole->getMovieTitle() . '</a><br>';
        }
        
        //  Get the show roles
        echo '<li><a id="personRoles"><h3>Show Roles</h3></a>';
        $tvShowRoles = $person->getTVShowRoles();
        echo '<b>' . $person->getName() . '</b> - Roles in <b>TVShows</b><br>';
        foreach ($tvShowRoles as $tvShowRole) {
            echo $tvShowRole->getCharacter() . ' in <a href="'.URLROOT.'/moviedatabase/shows?id=' . $tvShowRole->getTVShowID() . '">' . $tvShowRole->getTVShowName() . '</a><br>';
        }

?>
</div>
</div>