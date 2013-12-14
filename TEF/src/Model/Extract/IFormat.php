<?php

namespace Model\Extract;

use Entity;

interface IFormat
{
	public static function export(Entity $entity);
	public function exportText(String $text);
	public function import();
}
