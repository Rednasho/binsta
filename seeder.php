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
$newUser->username = 'testgebruiker-1';
$newUser->bio = 'Ik zal je eem wat vertellen mien beste keerl, ik ben een pro codeermeneer natuurlijk. Dat kan je zien aan de waanzinnige code in mijn eerste post!';
$newUser->password = password_hash('test', PASSWORD_DEFAULT);
$newUser->profile_img = 'test-pfp1.png';
$userId = R::store($newUser);

$newUser2 = R::dispense('users');
$newUser2->username = 'testgebruiker-2';
$newUser2->bio = 'Ik leef heel prive';
$newUser2->password = password_hash('test', PASSWORD_DEFAULT);
$newUser2->profile_img = 'test-pfp2.png';
$userId2 = R::store($newUser2);

$newPost = R::dispense('posts');
$newPost->likes = 0;
$newPost->description = 'Dit is een simpel stukje code die nooit iets zal loggen! Weet jij waarom??';
$newPost->code_input =
'if (1 + 1 == 2) { 
    console.log("Nou zeg.. dat klopt gewoon ook!");
}';
$newPost->code_language = 'javascript';
$newPost->user = $newUser;
$postId = R::store($newPost);

$newPost2 = R::dispense('posts');
$newPost2->likes = 0;
$newPost2->description = 'Een machtig stukje CSS #FML';
$newPost2->code_input =
'.blokjes {
    display: flex;
    flex-direction: column;
    margin: 10px 0 10px 0;
}';
$newPost2->code_language = 'css';
$newPost2->user = $newUser2;
$postId = R::store($newPost);

$likes = R::dispense('likes');
$likes->post = $newPost;
$likes->user = $newUser;
$likesId = R::store($likes);

$likes = R::load('likes', $likesId);
R::trash($likes);

$newComment = R::dispense('comments');
$newComment->comment = 'Hij logt niks omdat 1 en 2 niet als integer worden aangegeven waardoor 1 + 2 de string "12" zou worden';
$newComment->user = $newUser2;
$newComment->post = $newPost;
$commentId = R::store($newComment);

$newComment = R::dispense('comments');
$newComment->comment = 'Harstikke dondersmachtig mooi!!';
$newComment->user = $newUser;
$newComment->post = $newPost2;
$commentId = R::store($newComment);

