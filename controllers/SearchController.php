<?php

use RedBeanPHP\R as R;

class SearchController extends BaseController
{
    public function resultPost()
    {
        $searchPattern = '%' . trim($_POST['finduser']) . '%';
        $results = R::find('users', 'username LIKE :username', [':username' => $searchPattern]);
        $usersFound = count($results);

        displayTwig('/search/result.twig', ['results' => $results, 'usersFound' => $usersFound]);
    }

    public function profile($id)
    {

        $this->authorizeUser($_SESSION['userid']);

        $profile = R::find('users', 'id LIKE :id', [':id' => $id]);
        $posts = R::find('posts', 'user_id LIKE :userid', [':userid' => $id]);

        $profileImg = $this->getProfileImg();

        if ($id !== $_SESSION['userid']) {
            displayTwig('/search/profile.twig', ['profile' => $profile, 'posts' => $posts, 'profileImg' => $profileImg]);
        } else {
            displayTwig('/user/profile.twig', ['profile' => $profile, 'posts' => $posts, 'profileImg' => $profileImg]);
        }
    }
}