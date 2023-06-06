<?php

function displayTwig($template, $variables)
{
    $loader = new \Twig\Loader\FilesystemLoader('views', getcwd().'/..');
    $twig = new \Twig\Environment($loader, ['debug' => true]);
    $twig->display($template, $variables);
}

function error($errorCode, $errorMessage)
{
    http_response_code($errorCode);
    displayTwig('/error.twig', ['errorCode' => $errorCode, 'errorMessage' => $errorMessage]);
}