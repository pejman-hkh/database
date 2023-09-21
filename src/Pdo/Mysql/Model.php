<?php
namespace Pejman\Database\Pdo\Mysql;
class Model implements \Pejman\Database\Interface\ModelResult {
	use \Pejman\Database\Traits\Model;

	function __construct( $class ) {
		$this->db = \Pejman\Database\Wrapper::$database;
		$this->class = $class;
		$this->table = $this->class::$table;
	}
	public $paginateData;

	function paginate( $limit, $page = 1) {
		$this->paginateData = [ $limit, $page ];
		$from = (int)( $page * $limit - $limit );
		$limit = (int)$limit;

		$this->sql .= " limit ".($from > 0 ? $from : 0).", $limit";

		return $this;
	}

	function getPaginate() {
		$number = @$this->paginateData[0]?:1;
		$page = @$this->paginateData[1];
		unset($this->paginateData);

		$count = @$this->count;

		$limit = 4;
		$nP = ceil( $count / $number );
		$ret = new \StdClass;
		$ret->start = ( $page - $limit ) <= 0 ? 1 : $page - $limit;
		$ret->end = ( $page + $limit >= $nP ) ? $nP : $page + $limit;
		$ret->count = $count;
		$ret->endPage = ceil($count / $number);
		$ret->next = $page >= ceil( $count / $number ) ? $page : $page + 1;
		$ret->prev = $page <= 1 ? 1 : $page - 1;

		return $ret;
	}

	function makeQuery( $sql, $fields = '*' ) {
		if( substr( trim( $sql ), 0, 5 ) != 'select' ) {
			$this->sql = "select $fields from ".$this->table." ".($sql?:"");
		} else {
			$this->sql = $sql;
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

		$columns = $this->db->query("SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$this->table."' and table_schema = '".\Pejman\Database\Wrapper::$dbName."'  ")->find();

		foreach( $columns as $v ) {
			$this->columns[] = $v['COLUMN_NAME'];
			$this->columnsType[] = [ $v['COLUMN_NAME'],  $v['DATA_TYPE'] ];
		}
	}

	public $columns = [];
	public $columnsType = [];

	function save() {

		$this->getColumns();
	
		if( count( $this->columns ) == 0 ) {
			$this->columns[] = 'test';
		}

		$vals = [];
		foreach( $this->columns as $v ) {
			$type = gettype( $this->$v );
			if( is_array( $this->$v ) ) {
				$this->$v = implode(",", $this->$v);
			}
			
			if( $type == "integer" ) {
				$vals[] = (int)$this->$v;
			} else if( $type == "string" ) {
				$vals[] = (string)$this->$v;
			} else if( $type == "double" ) {
				$vals[] = (double)$this->$v;			
			} else {
				$vals[] = (string)$this->$v;
			}
		}

		if( @$this->recordExists ) {
			$vals[] = $this->id;
			$sql = "UPDATE `".$this->table."` SET ".'`'.implode('` = ?, `', $this->columns ).'` = ? '." WHERE id = ? ";

		} else {
			$sql = "INSERT INTO `".$this->table."`(".'`'.implode("` , `", @$this->columns ).'`'.") VALUES(".( str_repeat('?,', count( @$this->columns ) - 1 ).'?' ).")";
		}

		$this->db->query( $sql, $vals );

		if( ! $this->recordExists ) {
			$this->id = $this->db->lastInsertId();
		}

		$this->recordExists = 1;
		return $this->id;
	}

	function makeCountQuery() {
		$csql = $this->sql;
		$csql = substr($csql, strpos( $csql, 'from') );
		$csql = substr($csql, 0, strpos( $csql, 'limit') );

		$csql = preg_replace_callback('#order\s*by(.*?)(asc|desc)#isU', function( $m ) {
			return '';
		}, $csql);

		return "select count(*) as count ".$csql;
	}

	function count( $bind = [] ) {
		$fetch = $this->db->query( $this->makeCountQuery(), $bind )->find()[0];
		return $fetch->count;	
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
		$sql = "where 1 = 1 ";
		$bind = [];
		if( is_array( $a ) ) foreach( $a as $k => $v ) {
			$bind[] = $v;
			$sql .= " and $k = ? ";
		} else {
			$bind[] = $c;
			$sql .= " and $a $b = ? ";
		}

		$bind = array_merge( $bind, $this->bind );
		$this->makeQuery( $sql );

		return $this;
	}

}