<?php

namespace app\core\middlewares;

use app\core\Application;
use app\core\TemplateEngine;
use app\core\exception\ForbiddenException;

class AuthMiddleware extends BaseMiddleware
{
    public array $actions;

    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    public function execute()
    {
        if (Application::isGuest()) {
            $controller = TemplateEngine::getController();
            if ($controller && in_array($controller->action, $this->actions)) {
                throw new ForbiddenException();
            }
        }
    }
}
