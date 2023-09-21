<?php
namespace Pejman\Database\Interface;

interface Model {
	function __construct();

	function __set( $key, $value );

	function __get( $key );

	public static function sql( $sql, $bind = [] );

	function delete();

	function save();

	public static function find( $bind = [] );

	public static function field( $fields );

	public static function where( $a, $b = '', $c = '' );
}