<?php

class SV_GetUser_Helper
{
    public static function GetUser($userId)
    {
        if (!is_numeric($userId) || intval($userId) ===  0)
        {
            XenForo_Error::debug("user_id should be an integer:".var_export($userId,true));
        }
        static $userModel = null;
        static $userCache = null;
        if ($userModel === null)
        {
            $userModel = XenForo_Model::create('XenForo_Model_User');
        }
        if (isset($userCache[$userId]))
        {
            return $userCache[$userId];
        }
        else
        {
            $user = $userModel->getUserById($userId);
            if (empty($user))
            {
                $user = array('user_id'=> 0, 'username'=>'Guest');
            }
            $userCache[$userId] = $user ;
            
            return $user;
        }
    }

    public static function GetUserAvatar($content, $params, XenForo_Template_Abstract $template)
    {
        $user = array();
        if (isset($params['user_id']))
        {
            $userId = $params['user_id'];
            $visitor = XenForo_Visitor::getInstance();
            if ($userId == $visitor['user_id'])
            {
                $user = $visitor->toArray();
            }
            else
            {
                $user = self::GetUser($userId);
            }
        }
        $params['user'] = $user;
        return XenForo_Template_Helper_Core::callHelper('avatarhtml', array($user, false, $params, $content));
    }

    public static function GetUserName($content, $params, XenForo_Template_Abstract $template)
    {
        $user = array();
        $userId = empty($params) ? null : $params;
        $user = self::GetUser($userId);
        return XenForo_Template_Helper_Core::callHelper('usernamehtml', array($user, '', false, array()));
    }
}