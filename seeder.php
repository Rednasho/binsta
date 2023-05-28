<?php

require_once 'vendor/autoload.php';
use RedBeanPHP\R as R;

R::setup(
    'mysql:host=localhost;dbname=binsta',
    'bit_academy',
    'bit_academy'
);

R::nuke();

$newUser = R::dispense('users');
$newUser->username = 'kanjer.van.een.anjer';
$newUser->bio = 'Ik zal je eem wat vertellen mien beste keerl, ik ben een pro codeermeneer natuurlijk. Dat kan je zien aan de waanzinnige code in mijn eerste post!';
$newUser->password = password_hash('password123', PASSWORD_DEFAULT);
$newUser->profile_img = 'profile-placeholder.png';
$userId = R::store($newUser);

$newPost = R::dispense('posts');
$newPost->likes = 0;
$newPost->description = 'Dit is een simpel stukje code die nooit iets zal loggen! Weet jij waarom??';
$newPost->code_input =
'if (1 + 1 == 2) { 
    console.log("Wauw dat klopt!");
}';
$newPost->code_language = 'javascript';
$newPost->user = $newUser;
$postId = R::store($newPost);

$likes = R::dispense('likes');
$likes->post = $newPost;
$likes->user = $newUser;
$likesId = R::store($likes);

$likes = R::load('likes', $likesId);
R::trash($likes);

$newComment = R::dispense('comments');
$newComment->comment = 'Deze afbeelding is super tegek en leuk, gaaf hoor haha! Ha!!! ha... ha.....';
$newComment->user = $newUser;
$newComment->post = $newPost;
$commentId = R::store($newComment);