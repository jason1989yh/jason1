<?php 
class Oracle_model extends CI_Model{

	function insert($table,$data){		
		$this->db->insert($table, $data);
	}   

	function get_total_rows($table){
		$this->db->from($table);
		return $this->db->count_all_results();
	}


    
    function get_total_record($table){
        $query = $this->db->get($table);
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}
    
    function get_total_record_paging($table,$limit,$offset){
        $query = $this->db->get($table,$limit,$offset);
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}
    
    function get_total_record_sql($sql){
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0)
		{
			$result['datalist']=$query->result_array();
            $result['datacount']=$query->num_rows();
            return $result;
		}
    }
    
	
    function get_status_total_record($health=''){
        
        $this->db->select('*');
        $this->db->from('oracle_status ');
        if($health==1){
            $this->db->where("connect", 1);
        }
        
        !empty($_GET["host"]) && $this->db->like("host", $_GET["host"]);
        !empty($_GET["tags"]) && $this->db->like("tags", $_GET["tags"]);
        
        !empty($_GET["connect"]) && $this->db->where("connect", $_GET["connect"]);
        !empty($_GET["session_total"]) && $this->db->where("session_total >", (int)$_GET["session_total"]);
        !empty($_GET["session_actives"]) && $this->db->where("session_actives >", (int)$_GET["session_actives"]);
        if(!empty($_GET["order"]) && !empty($_GET["order_type"])){
            $this->db->order_by($_GET["order"],$_GET["order_type"]);
        }
        else{
            $this->db->order_by('tags asc');
        }
        
        $query = $this->db->get();
        if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
    }
    
    function get_tablespace_total_record(){
        
        $this->db->select('*');
        $this->db->from('oracle_tablespace ');
       
        !empty($_GET["host"]) && $this->db->like("host", $_GET["host"]);
        !empty($_GET["tags"]) && $this->db->like("tags", $_GET["tags"]);
        
        if(!empty($_GET["order"]) && !empty($_GET["order_type"])){
            $this->db->order_by($_GET["order"],$_GET["order_type"]);
        }
        else{
            $this->db->order_by('avail_size asc');
        }
        
        $query = $this->db->get();
        if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
    }

    function get_dataguard_total_record(){
        $query=$this->db->query("select t.id, 
                                        t.group_name,
                                        d1.`host` 			as p_host,
                                        d1.`port` 			as p_port,
                                        d1.dsn 				as p_dsn,
                                        p.`thread#` 		as p_thread,
                                        p.`sequence#` 	as p_sequence,
                                        p.curr_scn			as p_scn,
                                        p.curr_db_time as p_db_time,
                                        d2.`host`			as s_host,
                                        d2.`port`			as s_port,
                                        d2.dsn					as s_dsn,
                                        s.`thread#`		as s_thread,
                                        s.`sequence#`	as s_sequence,
                                        s.`block#`			as s_block,
                                        s.delay_mins,
                                        s.avg_apply_rate,
                                        s.curr_scn			as s_scn,
                                        s.curr_db_time	as s_db_time
                                from db_servers_oracle_dg t, oracle_dg_p_status p, oracle_dg_s_status s, db_servers_oracle d1, db_servers_oracle d2
                                where t.primary_db_id = p.server_id
                                    and t.standby_db_id = s.server_id
                                    and t.primary_dest_id = p.dest_id
                                    and p.server_id = d1.id
                                    and s.server_id = d2.id
                                order by t.display_order; ");
        if ($query->num_rows() > 0)
        {
           return $query->result_array(); 
        }
    }

    function get_dataguard_group(){
        $query=$this->db->query("select * from db_servers_oracle_dg where is_delete = 0 order by display_order, id; ");
        if ($query->num_rows() > 0)
        {
           return $query->result_array(); 
        }
    }
    
    function get_dg_group_by_id($id){
        $query=$this->db->query("select * from db_servers_oracle_dg where is_delete = 0 and id = $id; ");
        if ($query->num_rows() > 0)
        {
           return $query->result_array(); 
        }
    }

    
    function get_dg_process_info($group_id,$type){
        $query=$this->db->query("select * from db_oracle_dg_process where group_id = $group_id and process_type = '$type' order by id desc limit 1; ");
        if ($query->num_rows() > 0)
        {
           return $query->result_array(); 
        }
    }
    
    function get_mrp_status_by_id($id){
        $query=$this->db->query("select mrp_status from oracle_dg_s_status where server_id = $id order by id desc limit 1; ");
        if ($query->num_rows() > 0)
        {
        	 $result=$query->row();
           return $result->mrp_status; 
        }
    }

    function get_pri_id_by_group_id($id){
        $query=$this->db->query("select CASE is_switch
                                            WHEN 0 THEN primary_db_id
                                            ELSE standby_db_id
                                        END as pri_id
                                   from db_servers_oracle_dg
                                  where id = $id ");
        if ($query->num_rows() > 0)
        {
            $result=$query->row();
            return $result->pri_id;
        }
    }


    function get_sta_id_by_group_id($id){
        $query=$this->db->query("select CASE is_switch
                                            WHEN 0 THEN standby_db_id 
                                            ELSE primary_db_id
                                        END as sta_id
                                   from db_servers_oracle_dg
                                  where id = $id ");
        if ($query->num_rows() > 0)
        {
            $result=$query->row();
            return $result->sta_id;
        }
    }


    function get_primary_info($pri_id){
        $query=$this->db->query("select d.id,
                                    d.host         as p_host,
                                    d.port         as p_port,
                                    s.db_name      as db_name,
                                    s.open_mode    as open_mode,
                                    p.`thread#`    as p_thread,
                                    p.`sequence#`  as p_sequence,
                                    p.curr_scn     as p_scn,
                                    p.curr_db_time as p_db_time
                            from (select * from db_servers_oracle where id = $pri_id) d
                            left join oracle_status s
                                on d.id = s.server_id
                            left join (select *
                                    from oracle_dg_p_status
                                    where id in (select max(id)
                                                    from oracle_dg_p_status t
                                                    where server_id = $pri_id
                                                    group by `thread#`)) p
                                on d.id = p.server_id; ");
        if ($query->num_rows() > 0)
        {
           return $query->result_array(); 
        }
    }

    function get_standby_info($sta_id){
        $query=$this->db->query("select d.host as s_host,
                                        d.port as s_port,
                                        os.db_name  as db_name,
                                        os.open_mode  as open_mode,
                                        s.`thread#` as s_thread,
                                        s.`sequence#` as s_sequence,
                                        s.`block#` as s_block,
                                        s.delay_mins,
                                        s.avg_apply_rate,
                                        s.curr_scn       as s_scn,
                                        s.curr_db_time   as s_db_time,
                                        s.mrp_status     as s_mrp_status
                                  from (select * from db_servers_oracle where id = $sta_id) d
                                left join oracle_status os
                                    on d.id = os.server_id
                                left JOIN oracle_dg_s_status s
                                    on d.id = s.server_id
                                    order by s.id desc 
                                    limit 1; ");
        if ($query->num_rows() > 0)
        {
           return $query->result_array(); 
        }
    }

    
    
    function get_total_host(){
        $query=$this->db->query("select host  from mysql_status order by host;");
        if ($query->num_rows() > 0)
        {
           return $query->result_array(); 
        }
    }
    
    function get_total_application(){
        $query=$this->db->query("select application from mysql_status group by application order by application;");
        if ($query->num_rows() > 0)
        {
           return $query->result_array(); 
        }
    }

	function get_status_chart_record($server_id,$time){
        $query=$this->db->query("select * from oracle_status_history  where server_id=$server_id and YmdHi=$time limit 1; ");
        if ($query->num_rows() > 0)
        {
           return $query->row_array(); 
        }
    }
    
    

    
   
    function check_has_record($server_id,$time){
        $query=$this->db->query("select id from oracle_status_history where server_id=$server_id and YmdHi=$time");
        if ($query->num_rows() > 0)
        {
           return true; 
        }
        else{
            return false;
        }
    }
    
    

}

/* End of file oracle_model.php */
/* Location: ./application/models/oracle_model.php */