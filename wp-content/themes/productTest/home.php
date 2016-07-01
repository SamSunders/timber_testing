<?php
/**
 * Template Name: Home Page
 */
?>

<?php
$context = array();
$context['name'] = {{post.employee_name}};
$context['message2'] = "This is another thing!";
Timber::render('welcome.twig', $context);
