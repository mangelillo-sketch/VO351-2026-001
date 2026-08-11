<?php
setcookie("user_id", 345, strtotime("+1 year"));
setcookie("email", "email@me.it", strtotime("+1 year"));
setcookie("color", "red", strtotime("+1 year"));

echo "<pre>";
print_r($_COOKIE);
echo "</pre>";

unset($_COOKIE['color']);

echo "<pre>";
print_r($_COOKIE);
echo "</pre>";

echo $_COOKIE["user_id"], "<br />";
echo $_COOKIE["email"], "<br />";