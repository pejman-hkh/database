<?php
namespace Pejman\Database\Interface;

interface Model {
	function __construct();

	function __set( $key, $value );

	function __get( $key );

	public static function sql( $sql, $bind = [] );

	function delete();

	function save();

	function find( $bind = [] );

	function field( $fields );

	function where( $a, $b = '', $c = '' );
}