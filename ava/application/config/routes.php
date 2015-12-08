<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

// default route
$route['default_controller']	= 'map';
$route['404_override']			= '';
// default route END

$route['login/(:any)']		= 'login/$1';
$route['login']				= 'login';

$route['detail/(:any)']		= 'detail/index/$1';
$route['detail']			= 'detail';

$route['setting/(:any)']	= 'setting/$1';
$route['setting']			= 'setting';

$route['ws/(:any)']			= 'ws/$1';
$route['ws']				= 'ws';

$route['job/(:any)']		= 'job/$1';
$route['job']				= 'job';

$route['history/(:any)']	= 'history/$1';
$route['history']			= 'history';

$route['download']			= 'download';

/* End of file routes.php */
/* Location: ./application/config/routes.php */