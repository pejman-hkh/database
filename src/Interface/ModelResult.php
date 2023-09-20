<?php
namespace Pejman\Database\Interface;

interface ModelResult {
	function __construct( $class );

	function find( $bind = [] );

	function count( $bind = [] );

	function findFirst( $bind = [] );

	function paginate( $limit, $page = 1);

	function getPaginate();
}