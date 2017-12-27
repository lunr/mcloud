# James Movie Manager

### Features

* Add your favorite movies to your collection
* Search and sort your movies by title, format, release year, length and rating
* Login using your github account
* View popular movies from [The Movie DB's](https://www.themoviedb.org) collection of movies
* Click on the cover image of any popular movie to learn more
* Add any popular movie to your collection with one click of a button
* The movie information from a popular movie is automatically added to your collection using data from The Movie DB service

#### Technical Features

* The popular movies list on the main page is cached for 60 minutes to limit requests to The Movie DB's APIs.
* Uniquness of adding a popular movie to your collection is based on the movie title
* The movie rating for a movie from The Movie DB's data is on a scale of 10. The rating is calculated down to a scale of 5 to match the movie manager's rating system

#### Known Issues

* When editing a movie, if the data does not validate, and the user cancels the edit, the invalid data is still displayed in the view.
* The update controller does not validate that your user is allowed to edit/update the movie object
