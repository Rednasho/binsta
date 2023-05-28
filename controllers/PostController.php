<?php

use RedBeanPHP\R as R;

class PostController extends BaseController
{
    public function create($id)
    {
        $this->authorizeUser($id);

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
        $userId = $_SESSION['userid'];
        $postBean->user_id = $userId;
        R::store($postBean);


        header('location: /user/profile');
        exit();
    }
}