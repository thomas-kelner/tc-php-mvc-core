<?php

namespace app\core;

use app\controllers\Controller;
use app\core\Application;

class TemplateEngine
{
    public string $title = '';
    private static ?Controller $controller = null;

    public static function renderView(string $view, array $params = [])
    {
        $layout = self::getLayoutContent();
        $viewContent = self::getView($view, $params);

        return str_replace('{{content}}', $viewContent, $layout);
    }

    public static function renderContent(string $content)
    {
        $layout = self::getLayoutContent();

        return str_replace('{{content}}', $content, $layout);
    }

    private static function getLayoutContent()
    {
        $layout = Application::$app->layout;
        if (self::$controller) {
            $layout = self::$controller->layout;
        }
        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
        return ob_get_clean();
    }

    private static function getView(string $view, array $params = [])
    {
        extract($params);
        ob_start();
        include_once Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }


    public static function getController(): ?Controller
    {
        return self::$controller;
    }

    public static function setController(Controller $controller): void
    {
        self::$controller = $controller;
    }
}
