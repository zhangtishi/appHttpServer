<?php
/**
 * 查询数据库的封装类，基于底层数据库封装类，实现SQL生成器
 * 注：仅支持MySQL，不兼容其他数据库的SQL语法
 * @author Tianfeng.Han
 * @package SwooleSystem
 * @subpackage Database
 */
class class_sql
{

    public $table = '';
    public $primary = 'id';
    public $select = '*';
    public $sql = '';
    public $limit = '';
    public $where = '';
    public $order = '';
    public $group = '';
    public $having = '';
    public $join = '';
    public $union = '';
    public $for_update = '';

    //Union联合查询
    private $if_union = false;
    private $union_select = '';

    //Join连接表
    private $if_join = false;
    private $if_add_tablename = false;


    public $RecordSet;
	
    /**
     * 初始化
     */
	public function init()
	{
		 $this->table = '';
		 $this->primary = 'id';
		 $this->select = '*';
		 $this->sql = '';
		 $this->limit = '';
		 $this->where = '';
		 $this->order = '';
		 $this->group = '';
		 $this->having = '';
		 $this->join = '';
		 $this->union = '';
		 $this->for_update = '';
		 return $this;
	}
    /**
     * 字段等于某个值，支持子查询，$where可以是对象
     * @param $field
     * @param $_where
     */
    function equal($field, $_where)
    {
        if ($_where instanceof class_server_sql)
        {
            $where = $field.'=('.$_where->getsql().')';
        }
        else
        {
            $where = "`$field`='$_where'";
        }
        $this->where($where);
    }

    /**
     * 指定表名，可以使用table1,table2
     * @param $table
     */
    function from($table)
    {
        if (strpos($table,"`") === false)
        {
            $this->table= "`".$table."` ";
        }
        else{
            $this->table=$table;
        }
        return $this;
    }

    /**
     * 指定查询的字段，select * from table
     * 可多次使用，连接多个字段
     * @param $select
     * @return null
     */
    function select()
    {
        $arg_num = func_num_args();
        if($arg_num <  1)
        {
            return ;
        }
        elseif($arg_num ==   1)
        {
            $select =   func_get_arg(0);
        }
        elseif ($arg_num   >   1)
        {
            $arg_list = func_get_args();
            $select =   implode(',',$arg_list);
        }
        if ($this->select == "*")
        {
            $this->select = $select;
        }
        else
        {
            $this->select = $this->select . ',' . $select;
        }
        return $this;
    }

    /**
     * where参数，查询的条件
     * @param $where
     * @return null
     */
    function where($where)
    {
        //$where = str_replace(' or ','',$where);
        if($this->where==""  && $this->join == "")
        {
            $this->where="where ".$where;
        }
        else
        {
            $this->where=$this->where." and ".$where;
        }
        return $this;
    }


    /**
     * 相似查询like
     * @param $field
     * @param $like
     * @return null
     */
    function like($field,$like)
    {
        $this->where("`{$field}` like '{$like}'");
        return $this;
    }

    /**
     * 使用or连接的条件
     * @param $where
     * @return null
     */
    function orwhere($where)
    {
        if($this->where=="")
        {
            $this->where="where ".$where;
        }
        else
        {
            $this->where=$this->where." or ".$where;
        }
        return $this;
    }

    /**
     * 查询的条数
     * @param $limit
     * @return null
     */
    function limit($limit)
    {
        if (!empty($limit))
        {
            $_limit = explode(',', $limit, 2);
            if (count($_limit) == 2)
            {
                $this->limit = 'limit ' . (int)$_limit[0] . ',' . (int)$_limit[1];
            }
            else
            {
                $this->limit = "limit " . (int)$limit;
            }
        }
        else
        {
            $this->limit = '';
        }
        return $this;
    }

    /**
     * 指定排序方式
     * @param $order
     * @return null
     */
    function order($order,$func = 'asc')
    {
        if (!empty($order))
        {
            $this->order = "order by {$order}  {$func}";
        }
        else
        {
            $this->order = '';
        }
        return $this;
    }

    /**
     * 组合方式
     * @param $group
     * @return null
     */
    function group($group)
    {
        if(!empty($group))
        {
            $this->group = "group by $group";
        }
        else $this->group = '';
        return $this;
    }

    /**
     * group后条件
     * @param $having
     * @return null
     */
    function having($having)
    {
        if (!empty($having)) {
            $this->having = "having $having";
        } else {
            $this->having = '';
        }
        return $this;
    }

    /**
     * IN条件
     * @param $field
     * @param $ins
     * @return null
     */
    function in($field, $ins)
    {
        $ins = trim($ins, ','); //去掉2面的分号
        $this->where("`$field` in ({$ins})");
        return $this;
    }

    /**
     * NOT IN条件
     * @param $field
     * @param $ins
     * @return null
     */
    function notin($field,$ins)
    {
        $this->where("`$field` not in ({$ins})");
        return $this;
    }

    /**
     * INNER连接
     * @param $table_name
     * @param $on
     * @return null
     */
    function join($table_name,$on)
    {
        $this->join.="INNER JOIN `{$table_name}` ON ({$on})";
        return $this;
    }

    /**
     * 左连接
     * @param $table_name
     * @param $on
     * @return null
     */
    function leftjoin($table_name,$on)
    {
        $this->join.="LEFT JOIN `{$table_name}` ON ({$on})";
        return $this;
    }

    /**
     * 右连接
     * @param $table_name
     * @param $on
     * @return null
     */
    function rightjoin($table_name,$on)
    {
        $this->join.="RIGHT JOIN `{$table_name}` ON ({$on})";
        return $this;
    }


    /**
     * 主键查询条件
     * @param $id
     * @return null
     */
    function id($id)
    {
        $this->where("`{$this->primary}` = '$id'");
        return $this;
    }



    /**
     * 使SQL元素安全
     * @param $sql_sub
     * @return null
     */
   function sql_safe($str)
    {
		return trim($str);
    }
    /**
     * 获取组合成的SQL语句字符串
     * @param $ifreturn
     * @return string | null
     */
    function getsql($ifreturn = true,$del   =   true)
    {
        $this->sql = "select {$this->select} from {$this->table}";
        if($del)
        {
            $this->where(conf_table::$table_auto_field_delete." =  0");
        }
        $this->sql .= implode(' ',
            array(
                $this->join,
                $this->where,
                $this->union,
                $this->group,
                $this->having,
                $this->order,
                $this->limit,
                $this->for_update,
            ));

        if ($this->if_union)
        {
            $this->sql = str_replace('{#union_select#}', $this->union_select, $this->sql);
        }
        if ($ifreturn)
        {
            return $this->sql;
        }
    }

    /**
     * 锁定行或表
     * @return null
     */
    function lock()
    {
        $this->for_update = 'for update';
    }

    /**
     * SQL联合
     * @param $sql
     * @return null
     */
    function union($sql)
    {
        $this->if_union = true;
        if($sql instanceof class_server_sql)
        {
            $this->union_select = $sql->select;
            $sql->select = '{#union_select#}';
            $this->union = 'UNION ('.$sql->getsql(true).')';
        }
        else $this->union = 'UNION ('.$sql.')';
    }
    
    /**
     * 无重复插入replace 必须有主键或者唯一索引
     * @param unknown $data
     * @param string $ifreturn
     * @return string
     */
    function replace($data,$bind= false,$ifreturn = true)
    {
        $field="";
        $values="";
        foreach($data as $key => $value)
        {
            $field = $field . "`$key`,";            
            if(false == $bind)
            {
            	$values = $values . "'$value',";
            }else
            {
            	$values	=	$values . ":{$key},";
            }
            
        }
        $field = substr($field, 0, -1);
        $values = substr($values, 0, -1);
        $this->sql  =   "replace into {$this->table} ($field) values($values)";
        if ($ifreturn)
        {
            return $this->sql;
        }
    }

    /**
     * 执行插入操作
     * @param $data
     * @return bool
     */
    function insert($data,$bind= false,$ifreturn = true)
    {
        $field="";
        $values="";
        foreach($data as $key => $value)
        {
            $field = $field . "`$key`,";
            if(false == $bind)
            {
            	$values = $values . "'$value',";
            }else 
            {
            	$values	=	$values . ":{$key},";
            }
        }
        $field = substr($field, 0, -1);
        $values = substr($values, 0, -1); 
        $this->sql  =   "insert into {$this->table} ($field) values($values)";
        if ($ifreturn)
        {
            return $this->sql;
        }
    }

    /**
     * 对符合当前条件的记录执行update
     * @param $data
     * @return bool
     */
    function update($data,$bind = false,$ifreturn = true)
    {
        $update = "";
        foreach ($data as $key => $value) {
            if ($value != '' and $value{0} == '`') {
            	if(false == $bind)
            	{
                	$update = $update . "`$key`=$value,";
            	}else
            	{
            		$update = $update . "`$key`=:{$key},";
            	}
            } else {
            	if(false == $bind)
            	{
                	$update = $update . "`$key`='$value',";
            	}else
            	{
            		$update = $update . "`$key`=:{$key},";
            	}
            }
        }
        $update = substr($update, 0, -1);
        $this->sql  =   "update {$this->table} set {$update} {$this->where} {$this->limit}";
        if ($ifreturn)
        {
            return $this->sql;
        }
    }

    /**
     * 删除当前条件下的记录
     * @return bool
     */
    function delete($ifreturn = true)
    {
        $this->sql  =   "delete from {$this->table} {$this->where} {$this->limit}";
        if ($ifreturn)
        {
            return $this->sql;
        }
    }
}
