<?php

namespace app\core\exception;

class ForbiddenException extends \Exception
{
    protected $message = 'Você não tem permissão para acessar esta página.';
    protected $code = 403;
}
