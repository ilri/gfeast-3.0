<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Common_model extends CI_Model
{
	/* cheking for duplicate value*/
	public function __construct()
	{
		parent::__construct();
	}
	public function checkDuplicate($table, $where)
	{
		$result = $this->db->get_where($table, $where);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return false;
		}
	}
	/* common insert  */
	public function insert($table, $data, $where, $cols)
	{
		$result = $this->db->insert($table, $data);
		if ($result) {
			$id = $this->db->insert_id();
			$this->db->where($where, $id);
			$this->db->select($cols);
			$data = $this->db->get($table);
			return $data->row();
		} else {
			return false;
		}
	}
	/* common single select */
	public function select_single($table, $colsReturn, $where)
	{
		$this->db->select($colsReturn);
		if ($where !== "") {
			$this->db->where($where);
		}
		$result = $this->db->get($table);
		if ($result->num_rows() > 0) {
			return $result->row();
		} else {
			return false;
		}
	}

	/* common select all */
	public function select_all($table, $cols, $where)
	{
		if ($where !== "") {
			$this->db->where($where);
		}
		$this->db->select($cols);
		$result = $this->db->get($table);
		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return false;
		}
	}
	/* common select all */
	public function select_all_with_condation($table, $cols, $where, $or_where, $orderBy, $orderMod, $like_col1, $like_col2, $like_val, $limit)
	{
		if (count($where) !== 0) {
			$this->db->where($where);
		}
		if ($like_col1 != "") {
			$this->db->like($like_col1, $like_val);
		}
		if ($like_col2 != "") {
			$this->db->like($like_col2, $like_val);
		}
		if (count($or_where) !== 0) {
			$this->db->or_where($or_where);
		}

		if ($orderBy != "") {
			$this->db->order_by($orderBy, $orderMod);
		}
		if ($limit != "") {
			$this->db->limit($limit);
		}
		$this->db->select($cols);
		$result = $this->db->get($table);
    	// echo $this->db->last_query();exit;
		if ($result->num_rows() > 0) {
			return $result->result();
		} else {
			return false;
		}
	}
	/* common update */
	public function update($table, $data, $colsReturn, $where)
	{
		$this->db->where($where);
		$result = $this->db->update($table, $data);
		if ($result) {
			return $this->select_single($table, $colsReturn, $where);
		} else {
			return false;
		}
	}

	/* common delete */
	public function delete($table, $whereCondition)
	{
		$this->db->where($whereCondition);
		$result = $this->db->delete($table);
    	// echo $this->db->last_query();exit;
		return $result;
	}
	/* fetch the search data */

	/* count of records */
	public function recordCounts($cols, $table, $where)
	{
		if ($where) {
			$this->db->where($where);
		}
		$sql = $this->db->select($cols)
		->from($table)
		->get();
		$count = $sql->num_rows();
		return $count;
	}
	/* common insert_batch */
	public function common_insert_batch($table, $data)
	{
		return $this->db->insert_batch($table, $data);
	}
	/* join tables */
	public function join_tables($SelectCols, $table1, $table2, $match_id1, $join, $where)
	{
		if ($where) {
			$this->db->where($where);
		}
		$this->db->select($SelectCols);
		$this->db->from($table1);
		$this->db->join($table2, "$match_id1", "$join");
		$result = $this->db->get();
    	// echo $this->db->last_query();exit;

		if ($result) {
			return $result->result();
		} else {
			return false;
		}
	}
	public function select_join_three_tbl($cols, $table1, $table2, $table3, $match_id1, $match_id2, $join, $where)
	{
		if ($where) {
			$this->db->where($where);
		}
		$this->db->select($cols);
		$this->db->from($table1);
		$this->db->join($table2, "$match_id1", "$join");
		$this->db->join($table3, "$match_id2", "$join");
		$result = $this->db->get();
    	// echo $this->db->last_query();
    	// exit;
		if ($result) {
			return $result->result();
		} else {
			return false;
		}
	}
}
