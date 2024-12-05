<?php

namespace app\core;

use app\core\Router;
use app\core\Response;
use app\core\Request;
use app\core\Session;
use app\database\DatabaseConnection;
use app\logging\LoggerInterface;
use app\models\DbModel;
use app\models\User;
use app\core\TemplateEngine;

class Application
{
    public static string $ROOT_DIR;
    public static Application $app;

    public string $layout = 'main';
    public string $userClass;
    public Router $router;
    public Response $response;
    public Request $request;
    public ?DbModel $userModel;
    public ?User $user;
    public Session $session;
    public DatabaseConnection $connection;
    public LoggerInterface $logger;
    public TemplateEngine $template;

    public function __construct($rootPath, DatabaseConnection $connection, LoggerInterface $logger, $dbConfig)
    {
        $this->userClass = $dbConfig['userClass'];
        self::$app = $this;
        self::$ROOT_DIR = $rootPath;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->connection = $connection;
        $this->logger = $logger;

        $this->template = new TemplateEngine();

        $primaryValue = $this->session->get('user');
        if ($primaryValue) {
            $primaryKey = $this->userClass::primaryKey();
            $this->userModel = $this->userClass::findOne([$primaryKey => $primaryValue]);
        } else {
            $this->userModel = null;
        }

        if ($this->userModel) {
            $this->user = $this->userModel;
        } else {
            $this->user = null;
        }
    }

    public function login(DbModel $userModel)
    {
        $this->userModel = $userModel;
        $primaryKey = $userModel->primaryKey();
        $primaryValue = $userModel->{$primaryKey};
        $this->session->set('user', $primaryValue);
        $this->user = $userModel;
        return true;
    }

    public function logout()
    {
        $this->userModel = null;
        $this->user = null;
        $this->session->remove('user');
    }

    public static function isGuest()
    {
        return !self::$app->userModel;
    }

    public function run()
    {
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            //var_dump($e);
            $this->response->setStatusCode($e->getCode());
            echo TemplateEngine::renderView('_error', [
                'exception' => $e
            ]);
        }
    }
}
