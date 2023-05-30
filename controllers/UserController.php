<?php

use RedBeanPHP\R as R;

class UserController extends BaseController
{
    public function login($id, $session, $error)
    {
        $id = null;
        $session = null;
        $profileImg = $this->getProfileImg();
        displayTwig('/user/login.twig', ['error' => $error, 'profileImg' => $profileImg]);
    }

    public function loginPost()
    {
        $username = trim($_POST['username']);

        if (empty($username)) {
            header('location: ?error=emptyusn');
            exit();
        }

        $user = R::findOne('users', 'username LIKE :username', [':username' => $username]);

        if ($user) {
            if (empty(trim($_POST['password']))) {
                header('location: ?error=emptypwd');
                exit();
            }

            $password = $user->password;
            if (password_verify($_POST['password'], $password)) {
                session_start();
                $_SESSION['userid'] = $user->id;
                header('location: /user/profile');
                exit();
            } else {
                header('location: ?error=wrongpwd');
                exit();
            }
        } else {
            header('location: ?error=usernotfound');
            exit();
        }
    }

    public function signup($id, $session, $error)
    {
        $id = null;
        $session = null;
        $profileImg = $this->getProfileImg();
        displayTwig('/user/signup.twig', ['error' => $error, 'profileImg' => $profileImg]);
    }

    public function signupPost()
    {

        $username = htmlspecialchars(trim($_POST['username']));
        $username = preg_replace('/\s+/', '-', $username);
        $username = preg_replace('/-+/', '-', $username);
        $username = strtolower($username);

        $password = htmlspecialchars($_POST['password']);
        $passwordRepeat = htmlspecialchars($_POST['passwordrepeat']);

        $checkUsername = R::findOne('users', 'username LIKE :username', [':username' => $username]);

        if (empty($username)) {
            header('location: ?error=emptyusn');
            exit();
        }

        if ($checkUsername) {
            header('location: ?error=userexists');
            exit();
        }

        if (empty(trim($password))) {
            header('location: ?error=emptypwd');
            exit();
        }

        if ($password !== $passwordRepeat) {
            header('location: ?error=nopwdmatch');
            exit();
        }

        $newUser = R::dispense('users');
        $newUser->username = $username;
        $newUser->password = password_hash($password, PASSWORD_DEFAULT);
        $newUser->profileImg = 'profile-placeholder.png';
        $id = R::store($newUser);
        header('location: /user/login');
    }

    public function profile()
    {
        $this->authorizeUser($_SESSION['userid']);

        $profile = R::find('users', 'id LIKE :userid', [':userid' => $_SESSION['userid']]);
        $posts = R::find('posts', 'user_id LIKE :userid', [':userid' => $_SESSION['userid']]);


        $profileImg = $this->getProfileImg();

        foreach ($posts as $post) {
            $post->code_input = htmlspecialchars_decode($post->code_input);
        }


        displayTwig('/user/profile.twig', ['profile' => $profile, 'posts' => $posts, 'profileImg' => $profileImg]);
    }

    public function edit($id, $session, $error)
    {
        $id = null;
        $session = null;

        $profile = R::find('users', 'id LIKE :userid', [':userid' => $_SESSION['userid']]);

        $profileImg = $this->getProfileImg();
        displayTwig('/user/edit.twig', ['error' => $error, 'profile' => $profile, 'profileImg' => $profileImg]);
    }

    public function editPost()
    {

        $file = $_FILES['pfp-upload'];
        $username = htmlspecialchars(trim($_POST['username']));
        $username = preg_replace('/\s+/', '-', $username);
        $username = preg_replace('/-+/', '-', $username);
        $username = strtolower($username);
        $bio = htmlspecialchars(trim($_POST['bio']));
        $currentPassword = htmlspecialchars($_POST['currentpassword']);
        $newPassword = htmlspecialchars($_POST['newpassword']);
        $repeatPassword = htmlspecialchars($_POST['repeatpassword']);

        $userBean = R::load('users', $_SESSION['userid']);
        if (!empty($username)) {
            $userBean->username = $username;
        }
        if (!empty($currentPassword)) {
            if (password_verify($currentPassword, $userBean->password)) {
                if (empty($newPassword)) {
                    header('location: ?error=emptypwd');
                    exit();
                }
                if ($newPassword === $repeatPassword) {
                    $userBean->password = password_hash($newPassword, PASSWORD_DEFAULT);
                } else {
                    header('location: ?error=wrongrepeat');
                    exit();
                }
            } else {
                header('location: ?error=wrongpwd');
                exit();
            }
        }

        if ($file['error'] === UPLOAD_ERR_OK) {
            $maxFileSize = 10 * 1024 * 1024; // 10MB
            if ($file['size'] > $maxFileSize) {
                die('File size exceeds the maximum limit (10MB).');
            }

            $allowedExtensions = ['png', 'jpg'];
            $fileInfo = pathinfo($file['name']);
            $extension = strtolower($fileInfo['extension']);

            if (!in_array($extension, $allowedExtensions)) {
                error(409, 'Only PNG and JPG files are allowed!');
                exit();
            }

            $uniqueName = uniqid() . '.' . $extension;

            $destinationFolder = '../public/assets/imgs/profile/';

            $destinationPath = $destinationFolder . $uniqueName;

            if ($userBean->profile_img !== 'profile-placeholder.png') {
                unlink($destinationFolder . $userBean->profile_img);
            }

            move_uploaded_file($file['tmp_name'], $destinationPath);

            $userBean->profile_img = $uniqueName;
        }
        $userBean->bio = $bio;

        R::store($userBean);

        header('location: /user/profile');
        exit();
    }

    public function logout()
    {
        $this->authorizeUser($_SESSION['userid']);

        session_unset();
        session_destroy();
        header('location: /user/login');
        exit();
    }
}
