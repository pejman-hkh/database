<?php
namespace Pejman\Database\Pdo\Mysql;
class ModelResult implements \Pejman\Database\Interface\ModelResult {
	
	use \Pejman\Database\Traits\Pagination;

	function __construct( $class ) {
		$this->db = \Pejman\Database\Wrapper::$database;
		$this->class = $class;
		$this->table = $this->class::$table;
	}

	function makeQuery( $sql, $fields = '*' ) {
		$this->sql = \Pejman\Database\Query::makeSelectQuery( $sql, $this->table, $fields );
		return $this;
	}

	function sql( $sql, $bind = [] ) {
		return $this->makeQuery( $sql );
	}

	function delete( &$model ) {
		return $this->db->query( \Pejman\Database\Query::makeDeleteQuery( $this->table ), [ $model->id ] );
	}

	function save( &$model ) {
		list( $columns, $columnsType ) = Columns::init( $this->db, $this->table );

		$this->db->query( @$model->recordExists ? \Pejman\Database\Query::makeUpdateQuery( $this->table, $columns ) : \Pejman\Database\Query::makeInsertQuery( $this->table, $columns ), \Pejman\Database\Bind::make( $columns, $model ) );

		if( ! $model->recordExists )
			$model->id = $this->db->lastInsertId();
		
		$model->recordExists = 1;
		return $model->id;
	}

	function count( $bind = [] ) {
		return $this->db->query( \Pejman\Database\CountSql::make( $this->sql ), $bind )->find()[0]->count;
	}

	private $bind = [];

	function find( $bind = [] ) {

		if( count( $this->bind ) > 0 ) 
			$bind = array_merge( $bind, $this->bind );
		
		$query = $this->db->query( $this->sql, $bind );

		if( @$this->paginateData )
			$this->count = $this->count( $bind );

		$ret = [];
		while( $v = $query->next() ) {
			$o = new $this->class( $this->class );
			$o->recordExists = true;
			$o->setData( $v );
	
			$ret[] = $o;
		}

		$iter = new \Pejman\Database\MyIterator();
		$iter->append( $ret );
		$iter->pagination = $this->getPaginate();
		return $iter;	
	}

	function findFirst( $bind = [] ) {
		return $this->find( $bind )->index(0);
	}

	function field( $fields ) {
		return $this->makeQuery( " ", $fields );
	}

	function where( $a, $b = '', $c = '' ) {
		list( $sql, $bind ) = \Pejman\Database\Query::makeWhereQuery($a,$b,$c);
		$this->bind = array_merge( $bind, $this->bind );
		return $this->makeQuery( $sql );
	}

}