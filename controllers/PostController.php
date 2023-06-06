<?php

use RedBeanPHP\R as R;

class PostController extends BaseController
{
    public function create()
    {
        $this->authorizeUser($_SESSION['userid']);

        $profileImg = $this->getProfileImg();

        $user = R::find('users', 'id LIKE :id', [':id' => $_SESSION['userid']]);
        displayTwig('/post/create.twig', ['user' => $user, 'profileImg' => $profileImg]);
    }

    public function createPost()
    {
        $postBean = R::dispense('posts');
        $postBean->likes = 0;
        $postBean->description = htmlspecialchars(trim($_POST['description']));
        $postBean->code_input = htmlspecialchars(trim($_POST['code-input']));
        $postBean->code_language = htmlspecialchars(trim($_POST['code-language']));
        $postBean->user_id = $_SESSION['userid'];
        R::store($postBean);


        header('location: /user/profile');
        exit();
    }
}