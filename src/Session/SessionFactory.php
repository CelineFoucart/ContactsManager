<?php

namespace App\Session;

class SessionFactory
{
    
    private static ?SessionPHP $session = null;
    
    /**
     * Get a instance of SessionPHP
     * 
     * @return SessionPHP
     */
    public static function getSession(): SessionPHP
    {
        if(self::$session === null) {
            self::$session = new SessionPHP();
        }
        return self::$session;
    }

    /**
     * Get a new instance of Auth
     * 
     * @return Auth
     */
    public static function getAuth(): Auth
    {
        return new Auth(self::getSession());
    }

    /**
     * Get a new instance of FlashService
     * 
     * @return FlashService
     */
    public static function getFlash(): FlashService
    {
        return new FlashService(self::getSession());
    }
}