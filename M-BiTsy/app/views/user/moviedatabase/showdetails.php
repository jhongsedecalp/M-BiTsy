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
       
       echo '<a id="tvShowInfo"><h3>Full TVShow Info</h3></a>';
        $tvShow = $data['tmdb']->getTVShow($data['id']);
        echo '<b>' . $tvShow->getName() . '</b><br>';
        echo 'ID<br>' . $tvShow->getID() . '</br>';
        echo 'Overview<br>' . $tvShow->getOverview() . '<br>';
        echo 'Number of Seasons<br>' . $tvShow->getNumSeasons() . '</br>';
        echo 'Seasons<br>';
        $seasons = $tvShow->getSeasons();
        foreach ($seasons as $season) {
            echo '<a href="'.URLROOT.'/moviedatabase/season?id=' . $season->getID() . '">Season ' . $season->getSeasonNumber() . '</a><br>';
        }
        echo '<img src="' . $data['tmdb']->getImageURL('w185') . $tvShow->getPoster() . '"/>';

?>
</div>
</div>