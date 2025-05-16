<?php

use Illuminate\Support\Facades\Route;
use App\Models\Settings;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/////////////////////////
// Maintenance //////////
/////////////////////////

Route::get('/maintenance', function () {
	$general = Settings::findOrFail('1');
    return view('frontend.maintenance', compact('general'));
})->name('maintenance');

//Auth
Auth::routes();

//Frontend
Route::group(['namespace' => 'App\Http\Controllers\Frontend','middleware' => 'App\Http\Middleware\CheckMaintenance::class'], function () {
    //Home
    Route::get('/', 'HomeController@index')->name('home');
    //User Profile
    Route::get('/@{username}', 'UserController@index')->name('user-profile');
    //Edit Profile
    Route::get('/edit-profile', 'UserController@edit_profile')->name('edit-profile');
    //Update Profile
    Route::put('/update-profile', 'UserController@update_profile')->name('update_profile');
    //Update Profile
    Route::get('/delete-profile/{id}', 'UserController@delete_profile')->name('delete_profile');

    //Movie
	Route::get('/movie/{id}', 'DetailMovieController@index')->name('single-movie');
    //Series
	Route::get('/series/{id}', 'DetailSeriesController@index')->name('single-series');
    //Episodes
	Route::get('/{id}/{series_slug}/season-{series_season}/episode-{series_episode}', 'DetailEpisodeController@index')->name('single-episode');
    //Person
    Route::get('/person/{id}', 'DetailPersonsController@index')->name('single-person');
    //Collections
    Route::get('/collection/{id}', 'DetailCollectionsController@index')->name('single-collection');
    //Pages
    Route::get('/page/{id}', 'DetailPagesController@index')->name('single-page');

    //Lisitng Movies
    Route::get('/movies', 'ListingsController@movies')->name('movies-lists');
    //Lisitng Series
    Route::get('/series', 'ListingsController@series')->name('series-lists');
    //Lisitng Episodes
    Route::get('/episodes', 'ListingsController@episodes')->name('episodes-lists');
    //Trendings
    Route::get('/trendings', 'ListingsController@trendings')->name('trendings-lists');
    //Recommendeds
    Route::get('/recommendeds', 'ListingsController@recommendeds')->name('recommendeds-lists');
    //Lisitng People
    Route::get('/persons', 'ListingsController@persons')->name('persons-lists');
    //Lisitng People
    Route::get('/collections', 'ListingsController@collections')->name('collections-lists');
    //Listing Watchlists
    Route::get('/watchlists', 'ListingsController@watchlists')->name('watchlists-lists');
    //Lisitng Alpa
    Route::get('/start-with/{id}', 'ListingsController@alpa')->name('start-with-lists');
    //Lisitng Genres
    Route::get('/genres/{id}', 'ListingsController@genres')->name('genres_lists');
    //Lisitng Years
    Route::get('/years/{id}', 'ListingsController@years')->name('years_lists');
    //Lisitng Countries
    Route::get('/countries/{id}', 'ListingsController@countries')->name('countries_lists');
    //Lisitng Countries
    Route::get('/qualities/{id}', 'ListingsController@qualities')->name('qualities_lists');
    //Lisitng Searching
    Route::get('/search', 'ListingsController@search')->name('search-lists');

    //Comments
    Route::post('/movie-comments', 'DetailMovieController@comments');
    Route::get('/movie-delete-comment/{id}', 'DetailMovieController@deletecomments');
    Route::post('/series-comments', 'DetailSeriesController@comments');
    Route::get('/series-delete-comment/{id}', 'DetailSeriesController@deletecomments');
    Route::post('/episodes-comments', 'DetailEpisodeController@comments');
    Route::get('/episodes-delete-comment/{id}', 'DetailMovieController@deletecomments');


    //Seasons Episodes
	Route::get('/get-season-episodes/series-{series_id}/season-{season_id}', 'DetailSeriesController@getSeasonsEpisodes');
	Route::get('/get-all-episodes/series-{series_id}', 'DetailSeriesController@getAllEpisodes');
    //Like Dislike
    Route::post('/episode/{episode}/like', 'HomeController@EpisodeLikeStore');
    Route::delete('/episode/{episode}/like', 'HomeController@EpisodeLikeDestroy');
    //Like Dislike
    Route::post('/items/{items}/like', 'HomeController@LikeStore');
    Route::delete('/items/{items}/like', 'HomeController@LikeDestroy');
    //Watchlists
    Route::post('/items/{items}/watchlist', 'HomeController@watchlistStore');
});

//Backend
Route::group(['namespace' => 'App\Http\Controllers\Backend', 'prefix' => 'admin','middleware' => 'App\Http\Middleware\CheckAdmin::class'], function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');
	Route::get('/chart', 'DashboardController@chart')->name('api.chart');

    //Data
    Route::get('/data/get_genres', 'DataController@get_genres');
    Route::get('/data/get_quality', 'DataController@get_quality');
    Route::get('/data/get_actors', 'DataController@get_actors');
    Route::get('/data/get_directing', 'DataController@get_directing');
    Route::get('/data/get_writing', 'DataController@get_writing');
    Route::get('/data/get_countries', 'DataController@get_countries');
    Route::get('/data/get_keywords', 'DataController@get_keywords');
    Route::get('/data/get_items', 'DataController@get_items');
    Route::get('/data/get_series', 'DataController@get_series');

    //Movies
    Route::get('/movies', 'MoviesController@index')->name('movies');
    Route::get('/movies/add', 'MoviesController@add')->name('movies-add');
    Route::post('/movies/store', 'MoviesController@store')->name('movies-store');
    Route::get('/movies/edit/{id}', 'MoviesController@edit');
    Route::put('/movies/update/{id}', 'MoviesController@update')->name('movies-update');
    Route::get('/movies/delete/{id}', 'MoviesController@destroy')->name('movies-delete');
    // Fetch
	Route::get('/movies/get_movie_data/{movie_id}', 'MoviesController@get_movie_data')->name('get-movie-data');
    Route::get('/movies/check_tmdb/{movie_id}', 'MoviesController@check_tmdb')->name('check-tmdb');
    //Visible
    Route::post('/movies/visible', 'MoviesController@visible')->name('movies-visible');
    Route::post('/movies/feature', 'MoviesController@feature')->name('movies-feature');
    Route::post('/movies/recommended', 'MoviesController@recommended')->name('movies-recommended');

    //Series
    Route::get('/series', 'SeriesController@index')->name('series');
    Route::get('/series/add', 'SeriesController@add')->name('series-add');
    Route::post('/series/store', 'SeriesController@store')->name('series-store');
    Route::get('/series/edit/{id}', 'SeriesController@edit');
    Route::put('/series/update/{id}', 'SeriesController@update')->name('series-update');
    Route::get('/series/delete/{id}', 'SeriesController@destroy')->name('series-delete');
    // Fetch
	Route::get('/series/get_series_data/{series_id}', 'SeriesController@get_series_data')->name('get-serie-data');
    Route::get('/series/check_tmdb/{series_id}', 'SeriesController@check_tmdb')->name('check-series-tmdb');
    //Visible
    Route::post('/series/visible', 'SeriesController@visible')->name('series-visible');
    Route::post('/series/feature', 'SeriesController@feature')->name('series-feature');
    Route::post('/series/recommended', 'SeriesController@recommended')->name('series-recommended');

    //Series
    Route::get('/episodes', 'EpisodesController@index')->name('episodes');
    Route::get('/episodes/add', 'EpisodesController@add')->name('episodes-add');
    Route::post('/episodes/store', 'EpisodesController@store')->name('episodes-store');
    Route::get('/episodes/edit/{id}', 'EpisodesController@edit');
    Route::put('/episodes/update/{id}', 'EpisodesController@update')->name('episodes-update');
    Route::get('/episodes/delete/{id}', 'EpisodesController@destroy')->name('episodes-delete');

    // Fetch
	Route::get('/episodes/get_episodes_data/{series_id}', 'EpisodesController@get_series_data');
    Route::get('/episodes/check_tmdb/{episodes_id}', 'EpisodesController@check_tmdb')->name('check-episodes-tmdb');
    //Visible
    Route::post('/episodes/visible', 'EpisodesController@visible')->name('episodes-visible');
    Route::post('/episodes/feature', 'EpisodesController@feature')->name('episodes-feature');
    Route::post('/episodes/recommended', 'EpisodesController@recommended')->name('episodes-recommended');

    // Fetch Episodes Details
	Route::get('/episodes/get_series/{series_id}', 'EpisodesController@get_series_seasons')->name('get-series-seasons');
	Route::get('/episodes/get_series/{series_id}/{season_id}', 'EpisodesController@get_series_episodes')->name('get-series-episodes');
	Route::get('/episodes/get_series/{series_id}/{season_id}/{episode_id}', 'EpisodesController@get_episodes_data')->name('get-episodes-data');

    //Collections
    Route::get('/collections', 'CollectionsController@index')->name('collections');
    Route::get('/collections/add', 'CollectionsController@add')->name('collections-add');
    Route::post('/collections/store', 'CollectionsController@store')->name('collections-store');
    Route::get('/collections/edit/{id}', 'CollectionsController@edit');
    Route::put('/collections/update/{id}', 'CollectionsController@update')->name('collections-update');
    Route::get('/collections/delete/{id}', 'CollectionsController@destroy')->name('collections-delete');
    Route::post('/collections/visible', 'CollectionsController@visible')->name('collections-visible');

    //Persons
    Route::get('/persons', 'PersonsController@index')->name('persons');
    Route::get('/persons/add', 'PersonsController@add')->name('persons-add');
    Route::post('/persons/store', 'PersonsController@store')->name('persons-store');
    Route::get('/persons/edit/{id}', 'PersonsController@edit')->name('persons-edit');
    Route::put('/persons/edit/{id}', 'PersonsController@update')->name('persons-update');
    Route::get('/persons/delete/{id}', 'PersonsController@destroy')->name('persons-delete');
    // Fetch
    Route::get('/persons/get_person_data/{person_id}', 'PersonsController@get_person_data')->name('get-person-data');
    Route::get('/persons/check_person/{person_id}', 'PersonsController@check_person')->name('check-person');

    //Genres
    Route::get('/genres', 'GenresController@index')->name('genres');
    Route::post('/genres/store', 'GenresController@store')->name('genres-store');
    Route::put('/genres/edit/{id}', 'GenresController@update')->name('genres-update');
    Route::get('/genres/delete/{id}', 'GenresController@destroy')->name('genres-delete');
    //Visible
    Route::post('/genres/visible', 'GenresController@visible')->name('genres-visible');

    //Reports
    Route::get('/reports', 'ReportsController@index')->name('reports');
    Route::post('/reports/store', 'ReportsController@store')->name('reports-store');
    Route::post('/reports/solved', 'ReportsController@solved')->name('reports-solved');
    Route::get('/reports/delete/{id}', 'ReportsController@destroy')->name('reports-delete');

    //Comments
    Route::get('/comments', 'CommentsController@index')->name('comments-lists');
    Route::get('/comments/delete/{id}', 'CommentsController@destroy')->name('comments-delete');
    Route::post('/comments/approve', 'CommentsController@approve')->name('comments-approve');

    //Users
    Route::get('/users', 'UsersController@index')->name('members');
    Route::get('/users/add', 'UsersController@add')->name('members-add');
    Route::post('/users/store', 'UsersController@store')->name('members-store');
    Route::get('/users/edit/{id}', 'UsersController@edit');
    Route::put('/users/update/{id}', 'UsersController@update')->name('members-update');
    Route::get('/users/delete/{id}', 'UsersController@destroy')->name('members-delete');
    Route::post('/users/blocked', 'UsersController@blocked')->name('members-blocked');
    Route::post('/users/verify', 'UsersController@verify')->name('members-verify');

    //Pages
    Route::get('/pages', 'PagesController@index')->name('pages');
    Route::get('/pages/add', 'PagesController@add')->name('pages-add');
    Route::post('/pages/store', 'PagesController@store')->name('pages-store');
    Route::get('/pages/edit/{id}', 'PagesController@edit');
    Route::put('/pages/update/{id}', 'PagesController@update')->name('pages-update');
    Route::get('/pages/delete/{id}', 'PagesController@destroy')->name('pages-delete');

    //Settings
    Route::get('/settings', 'SettingsController@general')->name('general-settings');
	Route::put('/settings/general/update', 'SettingsController@update_general_settings')->name('update_general_settings');

    Route::get('/settings/search-engine', 'SettingsController@searchengine')->name('searchengine-settings');
	Route::put('/settings/search-engine/update', 'SettingsController@update_searchengine_settings')->name('update_searchengine_settings');

    Route::get('/settings/advertisements', 'SettingsController@advertisements')->name('advertisements-settings');
	Route::put('/settings/advertisements/update', 'SettingsController@update_advertisements_settings')->name('update_advertisements_settings');

});
