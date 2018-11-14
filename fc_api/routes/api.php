<?php

use GuzzleHttp\Client;
use Illuminate\Http\Request;

//'middleware' => 'client_credentials' staging.futbolconnect.com/v1/users/{user}/update-biography
Route::group(['prefix' => 'v1'], function () {
    Route::middleware(['auth:api', 'confirmed'])->group(function () {
        Route::post('isl', function () {
            return response(['status' => 'ok']);
        });
        Route::post('oauth/logout', 'AuthController@logout');
        Route::post('/pusher/auth', 'AuthController@pusher');

        /*consoles*/
        Route::get('/consoles', 'ConsoleController@index');
        Route::get('/users/{user}/game-per-console', 'GameController@index');
        Route::post('/users/choose-console', 'ConsoleUSerController@store');
        Route::post('/users/choose-game', 'GameUSerController@store');

        /*games*/
        Route::get('/users/games', 'GameController@index');

        /*Futbol types*/
        Route::get('/users/{user}/list-futbol-types', 'FutbolTypeController@index');
        Route::get('/users/choose-futbol-type', 'UserFutbolTypesController@index');

        //region selectores especialización de usuario y tipos de futbol
        Route::get('/resources/list-speciallities', 'ResourceController@listSpecialities');
        Route::get('/resources/list-futbol-types', 'ResourceController@listFuboltypes');
        //fin region
        //region Rutas Usuario
        Route::put('/users/{user}/update-biography', 'UsersController@updateBiographyAndNames');
        Route::post('general-information', 'GeneralUserProfileController@update');
        Route::post('categories', 'UserPreferenceController@store');
        Route::get('/users/{user}/technical-information', 'TechnicalInformationController@index');
        Route::post('/users/{user}/technical-information', 'TechnicalInformationController@store');

        Route::get('/users/{user}/avatar', 'UserAvatarController@show');
        Route::post('/users/{user}/avatar', 'UserAvatarController@store');

        Route::get('/users/{user}/cover', 'UserCoverController@index');
        Route::post('/users/{user}/cover', 'UserCoverController@store');
        //endregion
        //region Rutas
        Route::get('/suggestions', 'SuggestionController@index');
        //endregion
        //region Notifications
        Route::get('/notifications', 'NotificationController@index');
        Route::post('/marck-seen/notifications', 'NotificationController@marckUnSeen');
        Route::post('/notifications/{notification}', 'NotificationController@update')
            ->name('users.notifications.update');
        //endregion

        Route::get('representatives', 'UserRepresentativeController@index');
        Route::get('speak-languages', 'SpeakLanguageController@index')
            ->name('speak_languages.index');

        Route::get('game-positions', 'UserGamePositionController@index');

        Route::get('/users/{user}/images', 'UserImageController@index');
        Route::post('/file-images-upload', 'UserImageController@create');
        Route::post('/users/{user}/file-videos-upload', 'UserVideoController@create');
        //endregion

        //region User Posts
        Route::get('/users/{user}/posts', 'PostController@getPostsByUser');
        Route::get('posts', 'PostController@index');
        Route::post('/users/{user}/posts', 'PostController@create')->name('posts.create');
        Route::get('posts/{post}', 'PostController@show')->name('posts.show');
        Route::put('posts/{post}', 'PostController@update')->name('posts.update');
        Route::delete('/posts/{post}/delete', 'PostController@delete')->name('post.delete');

        /**Coments**/
        Route::get('/posts/{post}/comments', 'CommentController@index')->name('posts.comments.index');
        Route::post('/posts/{post}/comments', 'CommentController@store')->name('posts.comments.store');
        Route::put('comments/{comment}', 'CommentController@update')->name('posts.comments.update');
        Route::delete('comments/{comment}', 'CommentController@delete');

        /**Likes**/
        Route::post('/posts/{post}/likes', 'LikeController@store')->name('posts.likes.store');
        //endregion
        //region User Followers
        Route::post('users/{user}/follow', 'UserFollowerController@store')->name('user.follow');
        Route::post('users/{user}/unfollow', 'UserFollowerController@delete')->name('user.unfollow');

        //region userclubname, le asigna los clubes a los usuarios
        Route::post('/users/select-clubs-names', 'UserClubNameController@create');
        Route::get('/users/list-clubs-names', 'UserClubNameController@list');
        //end region

        //region
        Route::get('followings', function (Request $request) {
            try {
                $followings = $request->user()->followings;
                return response([
                    'message' => '',
                    'data' => new \App\Http\Resources\FollowerCollection($followings),
                ]);
            } catch (Exception $e) {
                return response($e);
            }

        });

        Route::get('followers', function (Request $request) {
            try {
                $followers = $request->user()->followers;
                return response([
                    'message' => '',
                    'data' => new \App\Http\Resources\FollowerCollection($followers),
                ]);
            } catch (Exception $e) {
                return response($e);
            }

        });
        //endregion

        //Route::get('users/get-friends', 'FriendController@index');
        Route::get('users/{user}/get-friends', 'FriendController@index');
        Route::post('users/{user}/friends-send', 'FriendController@sendAFriendRequest');
        Route::post('users/{user}/friends-cancel', 'FriendController@cancelFriendRequest');
        Route::post('users/{user}/friends', 'FriendController@acceptAFriendRequest');
        Route::post('users/{user}/friends-deny', 'FriendController@sendAFriendRequest');
        Route::delete('users/{user}/friends-delete', 'FriendController@acceptAFriendRequest');

        //region Conversations
        Route::get('conversations', 'ConversationController@index');
        Route::post('users/{user}/conversations', 'ConversationController@store');
        Route::get('conversations/{conversation}/messages', 'ConversationController@getMessages');
        Route::post('conversations/{conversation}/messages', 'ConversationController@storeMessage');
        //endregion

        /**Crud Perfil Player */
        Route::get('players', 'PlayerController@index');
        Route::post('players', 'PlayerController@store');
        Route::put('players/{player}', 'PlayerController@updatePlayer');
        Route::delete('players/{player}', 'PlayerController@delete');

        /** Crud Perfil Collaborator */
        Route::post('oauth/teams', 'AuthController@UserTeams');
        Route::post('collaborators', 'CollaboratorsController@store');
        Route::get('collaborator/{collaborator}/show-collaborator', 'CollaboratorsController@show');
        Route::get('collaborator', 'CollaboratorsController@index');
        Route::put('collaborator/{collaborator}', 'CollaboratorsController@update');
        //Route::delete('player/{player}', 'PlayerController@delete');

        /**Cambio de perfil USerProfile */
        Route::get('profiles', 'ProfileController@index');
        Route::post('profiles/{profile}/update-profile', 'ProfileUserController@updateUserProfile');

        /**Player Game*/
        Route::post('game/{player}/store', 'PlayerGamePositionController@store');
        Route::get('game/{player}/show', 'PlayerGamePositionController@index')->name('playergame.show');
        Route::get('game/{player}/show-game-position', 'PlayerGamePositionController@show');
        Route::delete('game/{player}', 'PlayerGamePositionController@delete');

        //region leagues
        Route::get('standings', 'LeagueController@getStandings');

        Route::get('user-leagues', 'LeagueUserController@index');
        Route::post('user-leagues', 'LeagueUserController@store');
        //endregion
    });

    //Route::post('users/{user}/upload-avatar', 'UsersController@saveAvatar');

    Route::apiResources([
        'users' => 'UsersController',
    ]);

    Route::post('users/{user}/validation', 'UsersController@validateCode');
    Route::post('users/account/password/reset', 'UsersController@getCodeResetPassword');;
    Route::post('users/{user}/password/update', 'UsersController@updatePassword');
    Route::post('users/{user}/update-user', 'UsersController@updateUserData');
    Route::get('users/{user}/show', 'UsersController@show');
    Route::post('player/{user}/store', 'PlayerController@store');

    Route::post('coachlevel/{user}/store', 'CoachLevelController@store');

    /* Club **/
    Route::get('/club/{club}/club', 'ClubNameController@show');
    Route::post('club/{user}/store', 'ClubNameController@store');
    Route::put('club/{club}/update-club', 'ClubNameController@updateClubName');
    Route::delete('club/{club}/delete-club', 'ClubNameController@delete');

    Route::get('teams', 'TeamController@index');
    Route::get('matches', 'MatchesController@getMatches');
    Route::get('matches/{match}', 'MatchesController@index');
    //region Leagues
    Route::get('leagues', 'LeagueController@index');
    //endregiom
    Route::get('/categories', 'CategoriesController@index');
    Route::get('countries', 'CountriesController@index');
    Route::post('countries', 'CountriesController@store');
    Route::get('countries/{country}/states', 'CountriesController@states');
    Route::get('states/{state}/cities', 'CountriesController@cities');
    Route::get('nationalities', 'NationalityController@index');
    Route::get('cities', 'CityController@index');
    Route::post('oauth/login', 'AuthController@login');
    Route::post('oauth/register', 'AuthController@store');
    Route::post('oauth/reset-password', 'AuthController@recoveryPassword');

    Route::get('rss-data', 'TeamController@schedulle');

    Route::get('hash_psw', function () {
        $psw = \Illuminate\Support\Facades\Hash::make('kdc*8510SANDRA');
        return $psw;
    });
});

Route::get('/test_fc_api', function () {
    $client = new Client(['base_uri' => 'https://sportickr.com']);
    $response = $client->request('GET', 'api/v1.0/api-key=d24ba528d78707c5a23e36abf9675beb&include=localteam,visitorteam,statistics,leage');

    $info = json_decode($response->getBody()->getContents());
    $i = 0;
    foreach ($info->data as $value) {
        $id = $value->id;
        $localteam_id = $value->localteam_id;
        $visitorteam_id = $value->visitorteam_id;
        $time = $value->time;
    }

    return response($info->data);
});
