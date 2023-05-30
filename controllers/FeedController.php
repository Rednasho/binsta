<?php

use RedBeanPHP\R as R;

class FeedController extends BaseController
{
    public function index($id, $userid)
    {
        $id = null; // Moet van BLAST

        $posts = R::findAll('posts');
        $users = R::findAll('users');
        $comments = R::findAll('comments');
        $likes = R::findAll('likes');

        $posts = array_reverse($posts);

        foreach ($posts as $post) {
            $post->code_input = htmlspecialchars_decode($post->code_input);
        }

        $profileImg = $this->getProfileImg();

        if (isset($userid)) {
            foreach ($posts as $post) {
                $liked = false;
                foreach ($likes as $like) {
                    if ($like->post_id == $post->id && $like->user_id == $userid) {
                        $liked = true;
                        break;
                    }
                }
                $post['liked'] = $liked ? 'yes' : 'no';
            }
        }

        displayTwig('/feed/index.twig', ['posts' => $posts, 'users' => $users, 'comments' => $comments, 'profileImg' => $profileImg]);
    }

    public function likePost($id)
    {
        if (!isset($_SESSION['userid'])) {
            header('Content-type: application/json');
            echo '{"error":"error=notloggedin"}';
            return;
        }

        $liked = $this->checkIfLiked($id, $_SESSION['userid']);
        if (!$liked) {
            $post = R::load('posts', $id);
            $post->likes++;
            R::store($post);

            $like = R::dispense('likes');
            $like->post_id = $id;
            $like->user_id = $_SESSION['userid'];
            $likedId = R::store($like);
            header("Content-type: application/json");
            echo '{"likes":' . $post->likes . ', "liked":"no"}';
            return;
        }
        $post = R::load('posts', $id);
        $post->likes--;
        R::store($post);

        $like = R::findOne('likes', 'post_id = :post_id AND user_id = :user_id', [':post_id' => $id, ':user_id' => $_SESSION['userid']]);
        if ($like !== null) {
            R::trash($like);
        }
        header("Content-type: application/json");
        echo '{"likes":' . $post->likes . ', "liked":"yes"}';
    }

    public function commentPost($id)
    {
        if (!isset($_SESSION['userid'])) {
            header('Content-type: application/json');
            echo '{"error":"error=notloggedin"}';
            return;
        }

        $comment = trim($_POST['comment']);
        $user = R::load('users', $_SESSION['userid']);

        if (empty($comment)) {
            exit();
        }

        $comments = R::dispense('comments');
        $comments->post_id = $id;
        $comments->user_id = $_SESSION['userid'];
        $comments->comment = $comment;
        $commentsId = R::store($comments);
        header('Content-type: application/json');
        echo '{"username":"' . $user->username . '", "userid":' . $comments->user_id . '}';
    }

    private function checkIfLiked($postId, $userId)
    {
        $post = R::load('posts', $postId);
        if ($post !== null) {
            $likes = R::count('likes', 'post_id = :post_id AND user_id = :user_id', [':post_id' => $postId, ':user_id' => $userId]);
            return ($likes > 0);
        }
        return false;
    }
}