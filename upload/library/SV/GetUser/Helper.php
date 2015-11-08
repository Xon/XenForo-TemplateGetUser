<?php

class SV_GetUser_Helper
{
    static $userModel = null;
    static $userCache = null;

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
                if (self::$userModel === null)
                {
                    self::$userModel = XenForo_Model::create('XenForo_Model_User');
                }
                if (isset(self::$userCache[$userId]))
                {
                    $user = self::$userCache[$userId];
                }
                else
                {
                    $user = self::$userModel->getUserById($userId);
                    if (empty($user))
                    {
                        $user = array('user_id'=> 0, 'username'=>'Guest');
                    }
                    self::$userCache[$userId] = $user ;
                }
            }
        }
        $params['user'] = $user;
        return XenForo_Template_Helper_Core::callHelper('avatarhtml', array($user, false, $params, $content));
    }

    public static function GetUserName($content, $params, XenForo_Template_Abstract $template)
    {
        $user = array();
        $userId = empty($params) ? null : $params;
        if (self::$userModel === null)
        {
            self::$userModel = XenForo_Model::create('XenForo_Model_User');
        }
        if (isset(self::$userCache[$userId]))
        {
            $user = self::$userCache[$userId];
        }
        else
        {
            $user = self::$userModel->getUserById($userId);
            if (empty($user))
            {
                $user = array('user_id'=> 0, 'username'=>'Guest');
            }
            self::$userCache[$userId] = $user ;
        }
        return XenForo_Template_Helper_Core::callHelper('usernamehtml', array($user, '', false, array()));
    }
}