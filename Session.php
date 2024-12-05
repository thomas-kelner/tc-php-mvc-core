<?php

namespace app\core;

class Session //gerencia sessões PHP e fornece funcionalidade para mensagens flash
{
    protected const FLASH_KEY = 'flash_messages';

    public function __construct() //Marca todas as mensagens existentes para remoção após serem exibidas
    {
        session_start();
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => $flashMessage) {
            $flashMessage['remove'] = true;
        }

        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    public function setFlash($key, $message) //Armazena uma mensagem flash na sessão
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,        //Indica que a mensagem não deve ser removida ainda
            'value' => $message
        ];
    }

    public function getFlash($key) //Recupera o valor de uma mensagem flash específica
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        return $_SESSION[$key] ?? false;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public function __destruct() //Remove mensagens flash marcadas para serem excluídas.
    {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => $flashMessage) {
            if ($flashMessage['remove']) { //Verifica se a mensagem deve ser removida
                unset($flashMessages[$key]); //Remove a mensagem da sessão
            }
        }

        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }
}
