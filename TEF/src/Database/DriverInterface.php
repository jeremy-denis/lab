<?php

namespace Database;

interface DriverInterface
{
	function save($arrayData, $table);
	function findOneById($id, $table);
	function findall($table);
}
