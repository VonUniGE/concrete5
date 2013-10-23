<?
defined('C5_EXECUTE') or die("Access Denied.");
use Symfony\Component\HttpKernel;
class Concrete5_Library_ControllerRouteCallback extends RouteCallback {

	public function execute(Request $request, Route $route, $parameters) {
		$resolver = new HttpKernel\Controller\ControllerResolver();
	    $callback = $resolver->getController($request);
	    $arguments = $resolver->getArguments($request, $callback);
	    $controller = $callback[0];
	    $method = $callback[1];
		$controller->on_start();
		$controller->runAction($method, $arguments);
	    $view = $controller->getViewObject();
	    if (is_object($view)) {
		    $view->setController($controller);
			if (isset($view) && $view instanceof View) {
				$content = $view->render();
			}
		}
		$response = new Response();
		$response->setContent($content);
		return $response;
	}

	public static function getRouteAttributes($callback) {
		$attributes = array();
		$attributes['_controller'] = $callback;
		$callback = new static($callback);
		$attributes['callback'] = $callback;
		return $attributes;
	}

}