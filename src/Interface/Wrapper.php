<?php
namespace Pejman\Database\Interface;

interface Wrapper {
	function connect( $config );

	function setConfig( $config = [] );

	function query( $sql, $bind = [] );

	function __destruct();

}