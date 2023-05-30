<?php

use RedBeanPHP\R as R;

class BaseController
{
    public function authorizeUser($id)
    {
        $path = '/user/login';
        if (!isset($id)) {
            $path = '/user/login';
        }
        if (!isset($_SESSION['userid'])) {
            header('location: ' . $path);
            exit();
        }
    }

    public function getProfileImg()
    {
        $profileImg = '/default/profile-placeholder.png';

        if (isset($_SESSION['userid'])) {
            $profile = R::find('users', 'id LIKE :userid', [':userid' => $_SESSION['userid']]);

            foreach ($profile as $profileItem) {
                if ($profileItem->profile_img !== 'profile-placeholder.png') {
                    $profileImg = '/profile/' . $profileItem->profile_img;
                }
            }
        }
        return $profileImg;
    }
}