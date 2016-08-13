Admin laravel panel

Install composer require greenelf/panel dev-master

<b>Add custom dashboard</b>
Add in your route.php file new route and set route name 'CustomDashboard'.
Example:  Route::get('/test/all', ['as' => '<b>CustomDashboard</b>', 'uses' => 'TestController@index']);
