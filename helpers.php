<?php

function displayTwig($template, $variables)
{
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/views');
    $twig = new \Twig\Environment($loader);
    $twig->display($template, $variables);
}

function error($errorCode, $errorMessage)
{
    http_response_code($errorCode);
    displayTwig('/error.twig', ['errorCode' => $errorCode, 'errorMessage' => $errorMessage]);
}