<?php
namespace Pejman\Database\Interface;

interface ModelResult {
	function __construct( $class );

	function find();

	function count();

	function findFirst();

	function paginate();

	function getPaginate();
}