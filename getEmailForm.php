<?php

function getEmailForm(){

$form = "<form action = '' method='post'>";
$form .= "<p>Your email <textarea rows='1' cols='50' name='email' ></textarea></p>";
$form .= "<input type='hidden' name='action' value='storeEmail'>";





$form .= "<p><input type='submit' value='submit'></p></form>";
$form .= "<p>To get a confirmation code to enable you to use the chart printer please enter your email and hit submit.  The confirmation code will store a cookie on your computer that gives you access.  You can remove the cookie but then you'll need to enter your email again.  If your email isn't recognised, please let us know at a rehearsal.</p>";
return $form;
}
