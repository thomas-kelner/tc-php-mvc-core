<?php

namespace app\core;

class Router
{
    private array $routes = [];
    private Response $response;
    private Request $request;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {
        $method = $this->request->method();
        $path = $this->request->getPath();

        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            $this->response->setStatusCode(404);
            $exception = new \Exception("Página não encontrada", 404);
            return TemplateEngine::renderView('_error', ['exception' => $exception]);
        };

        if (is_string($callback)) {
            return TemplateEngine::renderView($callback);
        }

        if (is_array($callback)) {
            $controller = new $callback[0]();
            TemplateEngine::setController($controller);
            $controller->action = $callback[1];
            $callback[0] = $controller;

            foreach ($controller->getMiddlewares() as $middleware) {
                $middleware->execute();
            }
        }

        return call_user_func($callback, $this->request, $this->response);
    }
}
