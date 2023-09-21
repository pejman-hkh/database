<?php
namespace Pejman\Database\Pdo\Mysql;
class Model implements \Pejman\Database\Interface\ModelResult {
	use \Pejman\Database\Traits\Model;
	use \Pejman\Database\Traits\Pagination;

	function __construct( $class ) {
		$this->db = \Pejman\Database\Wrapper::$database;
		$this->class = $class;
		$this->table = $this->class::$table;
	}

	function makeQuery( $sql, $fields = '*' ) {
		$this->sql = $sql;
		if( substr( trim( $sql ), 0, 5 ) != 'select' ) {
			$this->sql = "select $fields from ".$this->table." ".($sql?:"");
		}
	}

	function sql( $sql, $bind = [] ) {
		$this->makeQuery( $sql );

		return $this;	
	}

	function delete() {
		return $this->db->query( "DELETE FROM ".$this->table." where id = ? ", [ $this->id ] );
	}

	public function getColumns( $cache = true ) {
		$columns = new Columns;
		list( $this->columns, $this->columnsType ) = $columns->init();
	}

	public $columns = [];
	public $columnsType = [];

	function save() {
		$this->getColumns();
		$vals = Pejman\Database\Bind::make( $this->columns, $this->data );

		if( $this->recordExists )
			$vals[] = $this->id;

		$this->db->query( @$this->recordExists ? \Pejman\Database\Query::getUpdateQuery( $this->table, $this->columns ) : \Pejman\Database\Query::getInsertQuery( $this->table, $this->columns ), $vals );

		if( ! $this->recordExists )
			$this->id = $this->db->lastInsertId();
		
		$this->recordExists = 1;
		return $this->id;
	}

	function makeCountQuery() {
		return \Pejman\Database\CountSql::make( $this->sql );
	}

	function count( $bind = [] ) {
		return $this->countSql( $bind );
	}

	function countSql( $bind = [] ) {
		$fetch = $this->db->query( $this->makeCountQuery(), $bind )->find()[0];

		return $fetch->count;	
	}

	private $bind = [];

	function find( $bind = [] ) {
		$class = $this->class;

		if( count( $this->bind ) > 0 ) {
			$bind = array_merge( $bind, $this->bind );
		}

		$query = $this->db->query( $this->sql, $bind );

		if( @$this->paginateData ) {
			$this->count = $this->countSql( $bind );
		}

		$ret = [];
		while( $v = $query->next() ) {	
			$o = new $class();
			$o->recordExists = true;
			$o->setData( $v );
	
			$ret[] = $o;
		}

		return $ret;	
	}

	function findFirst( $bind = [] ) {
		return $this->find( $bind )[0];
	}

	function field( $fields ) {
		$this->makeQuery( " ", $fields );
		return $this;
	}

	function where( $a, $b = '', $c = '' ) {
		list( $sql, $bind ) = \Pejman\Database\Query::makeWhereQuery($a,$b,$c);
		$this->bind = array_merge( $bind, $this->bind );
		$this->makeQuery( $sql );
		return $this;
	}

}