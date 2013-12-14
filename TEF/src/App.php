<?php
session_start();

use Exception\HttpException;
use Exception\ExceptionHandler;
use Routing\Route;
use Http\Request;
use Http\Response;

class App {
	
	private static $instance = null;
	private $roads = array();
	private $baseUri;
	private $driver;
	private $appVariable;
	private $ip;
	
	public static function getInstance($templateEngine,$name,$driver,$server,$init=null)
	{
		if (App::$instance == null)
		{
			App::$instance = new \App($templateEngine,$name,$driver,$server);
			if ($init != null)
			{
				$init();
			}
		}
		return App::$instance;
	}
	
	public function __construct($templateEngine,$baseUri,$driver,$ip="")
	{  
        $exceptionHandler = new ExceptionHandler($templateEngine);
        set_exception_handler(array($exceptionHandler, 'handle'));
        $this->templateEngine = $templateEngine;
        $this->baseUri = $baseUri;
        $this->ip = $ip;
        $this->driver = $driver;
        $this->appVariable = array();
	}
	
	public function getDriver()
	{
		return $this->driver;
	}
	
	public function newRoad($pattern, $callable)
	{
		$_SESSION['page'] = $pattern;
		$this->addRoute($pattern,$callable);
		return $this;
	}
	
	public function getLang()
	{
		/*$lang = null;
		$tradLang = 'en_EN';	
		if (isset($_GET['lang']))
		{	
			$changeLang = $_GET['lang'];
			$tradLang = $changeLang;
		}
		if (!isset($_SESSION['lang']) || isset($changeLang) && $changeLang != $_SESSION['lang'])
		{
			$langfinder = new Database\LangFinder($tradLang, $this->driver);
			$lang = array();
			$langTemp = $langfinder->findAll();
			
			foreach ($langTemp as $key => $value)
			{
				$lang[$key] = $value;
			}
			$_SESSION['lang'] = $tradLang;
			$_SESSION['currentlang'] = $lang;
		}
		else
		{
			$lang = $_SESSION['currentlang'];
		}
		return $lang; 
	*/}	
	
	public function render($template, array $parameters = array(), $statusCode = 200)
    {
		$lang = $this->getLang();
		if ($lang != null)
		{
			$parameters['lang'] = $lang;	
		}
		return $this->templateEngine->render($template, $parameters);
    }
	
	public function findRoute($uri)
	{
		$nbElem = count($this->roads);
		for ($i=$nbElem;--$i>=0;) 
        {
			$siteUri = sprintf("/%s",$this->baseUri);
			$uriRequest = str_replace($siteUri,"",$uri);
            if ($this->roads[$i]->match($uri)) 
            {
                return $this->roads[$i];
            }
        }
        return null;
	}
	
	private function process(Request $request, Route $route)
    {		
        try 
        {					
			$arguments = $route->getArguments();
			array_unshift($arguments, $request);
			
			$response = call_user_func_array($route->getCallable(), $arguments);
			if (!($response instanceof Response)) 
			{
				$response = new Response($response);
			}
			$response->send();
        } 
        catch (HttpException $e) 
        {
            throw $e;
        } 
        catch (\Exception $e)
        {
            throw new HttpException(500, null, $e);
        } 
    }
	
	
	 public function run(Request $request = null)
    {
		if ($request === null)
		{
			$request = Request::createFromGlobals();
		}
		
    	$uri    = $request->getUri();
		
		$route = $this->findRoute($uri);
		if ($route != null)
		{
			return $this->process($request, $route);
		}
        
       throw new HttpException(404, 'Page Not Found');
    }
    
    public function addRoute($pattern, $callable)
    {
		$this->roads[] = new Route($pattern, $callable);
	}
	
	public function redirect($pattern)
	{
		header("Location:$pattern");
		exit;
	}
}
