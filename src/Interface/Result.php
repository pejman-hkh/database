<?php
namespace Pejman\Database\Interface;

interface Result {
	function __construct( $statement, $wrapper );

	function next();

	function find();

	function count();
}