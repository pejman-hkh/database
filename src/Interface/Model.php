<?php
namespace Pejman\Database\Interface;

interface Model {
	function __construct();

	function __set();

	function __get();

	function sql( $sql, $bind = [] );

	function delete();

	function save();

	function find();

	function field();

	function where();
}