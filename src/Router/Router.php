<?php

namespace App\Router;

use AltoRouter;
use App\Notification\notify;
use Exception;

class Router
{

    /**
     * @var string
     */
    private $viewPath;

    /**
     * @var AltoRouter
     */
    private $router;
    private $authUser;
    private $debugBar;

    public function __construct(string $viewPath, ?object $auth = null, ?object $debugBar = null)
    {
        $this->viewPath = $viewPath;
        $this->router = new AltoRouter();

        if (!is_null($auth)) {
            $this->authUser = $auth;
        }
        if (!is_null($debugBar)) {
            $this->debugBar = $debugBar;
        }
    }

    /**
     * @throws Exception
     */
    public function get(string $url, string $view, ?string $name = null): self
    {
        $this->router->map('GET', $url, $view, $name);
        return $this;
    }

    /**
     * @throws Exception
     */
    public function post(string $url, string $view, ?string $name = null): self
    {
        $this->router->map('POST', $url, $view, $name);
        return $this;
    }

    /**
     * @throws Exception
     */
    public function match(string $url, string $view, ?string $name = null): self
    {
        $this->router->map('POST|GET', $url, $view, $name);
        return $this;
    }

    /**
     * @throws Exception
     */
    public function url(string $routeName, array $params = []): string
    {
        return (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $this->router->generate($routeName, $params);

    }

    public function run(): self
    {
        $match = $this->router->match();
        $router = $this;
        if (!empty($this->authUser)) {
            $authUser = $this->authUser;
        }
        if (!empty($this->debugBar)) {
            $debugbarRenderer = $this->debugBar;
        }
        $notify = new notify();
        $is_API = is_array($match) && str_contains($match['target'], 'API');

        if (mode_maintenance) {
            ob_start();
            require $this->viewPath . DIRECTORY_SEPARATOR . '404/MaintenanceMode.php';
        } else {
            if (is_array($match)) {
                $view = $match['target'];
                $params = $match['params'];
                ob_start();
                require $this->viewPath . DIRECTORY_SEPARATOR . $view . '.php';
            } else {
                ob_start();
                require $this->viewPath . DIRECTORY_SEPARATOR . '404/Error404.php';
            }
        }

        $content = ob_get_clean();
        if ($is_API === false) {
            require $this->viewPath . DIRECTORY_SEPARATOR . 'layouts/default.php';
        } else {
            echo $content;
        }

        return $this;
    }
}