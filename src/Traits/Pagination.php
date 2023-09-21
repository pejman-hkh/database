<?php
namespace Pejman\Database\Traits;
trait Pagination {
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
}