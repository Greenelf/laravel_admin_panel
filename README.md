Admin laravel panel

Install composer require greenelf/panel dev-master

<b>Add custom dashboard</b><p>
Add in your route.php file new route and set route name 'CustomDashboard'.<p>
Example:  Route::get('/test/all', ['as' => '<b>CustomDashboard</b>', 'uses' => 'TestController@index']);
