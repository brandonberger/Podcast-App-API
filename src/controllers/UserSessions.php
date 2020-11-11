<?php
namespace Controllers;
use Propel\Runtime\ActiveQuery\Criteria;


class UserSessions {
    
    public function authenticateUser($userId, $sessionCrypt)
    {
        if (isset($sessionCrypt)) {
            // Decode Hash
            $hash = substr($sessionCrypt, 0, -23);
            $salt = substr($sessionCrypt, strlen($hash)+14, -1);
        
            $session = \Models\Users\UserSessionsQuery::create()->filterByUserId($userId)->filterByHash($hash)->filterBySalt($salt)->findOne();
            
            if (isset($session)) {
                return true;
            } else {
                return false;
            }
        }
    }

}
