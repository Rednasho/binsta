<?php

use RedBeanPHP\R as R;

class FeedController extends BaseController
{
    public function index($id, $userid)
    {
        $posts = R::findAll('posts');
        $users = R::findAll('users');
        $comments = R::findAll('comments');
        $likes = R::findAll('likes');

        $posts = array_reverse($posts);

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
            }
            $post['liked'] = $liked ? 'yes' : 'no';
        }

        displayTwig('/feed/index.twig', ['posts' => $posts, 'users' => $users, 'comments' => $comments, 'profileImg' => $profileImg]);
    }

    public function like($id, $userid)
    {
        $this->authorizeUser($userid);

        $liked = $this->checkIfLiked($id, $userid);
        if (!$liked) {
            $post = R::load('posts', $id);
            $post->likes++;
            R::store($post);

            $like = R::dispense('likes');
            $like->post_id = $id;
            $like->user_id = $_SESSION['userid'];
            $likedId = R::store($like);

            header('location: /?liked');
            exit();
        }
        $post = R::load('posts', $id);
        $post->likes--;
        R::store($post);

        $like = R::findOne('likes', 'post_id = :post_id AND user_id = :user_id', [':post_id' => $id, ':user_id' => $userid]);
        if ($like !== null) {
            R::trash($like);
        }

        header('location: /?unliked');
        exit();
    }

    public function commentPost($id)
    {

    }

    private function checkIfLiked($postId, $userId) {
        $post = R::load('posts', $postId);
        if ($post !== null) {
            $likes = R::count('likes', 'post_id = :post_id AND user_id = :user_id', [':post_id' => $postId, ':user_id' => $userId]);
            return ($likes > 0);
        }
        return false;
    }
}