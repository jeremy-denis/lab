<?php

namespace Database;

interface Finder
{
	public function findOneById($id);
	public function findAll();
	public function getEntityTable();
	public function getColumnId();
}
