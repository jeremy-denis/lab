<?php
start_session();

if (isset($_SESSION['message']))
{
	echo $_SESSION['message'];
	unset($_SESSION['message']);
}
