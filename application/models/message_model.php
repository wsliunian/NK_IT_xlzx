<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//update by L 2012.7.20
//更新内容:
//add send()
//add get_info()

//----------------------------函数列表----------------------------
/*
 * send($post,$receive,$content)		send message
 * @post		poster id
 * @receive		receiver id
 * @content		message content
 * @return		true|error info
 * */
/*
 * get_info($id)		get message info
 * @id			message id
 * @return 		message info|error info
 * */
//---------------------------------------------------------------

class message_model extends CI_model
{
	function send($post,$receive,$content)
	{
		if($this->user_model->exist($post)&&$this->user_model->exist($receive))
		{
			//create message
			$content=$this->db->escape($content);
			$this->db->query("insert into message(post,receive,content) values($post,$receive,$content)");
			$message_id=$this->db->insert_id();
			
			//fresh poster list
			$list_post=$this->db->query("select people from list where owner=$post")->row_array();
			$list_post=json_decode($list_post,true);
			if(isset($list_post[$receive])){$list_post[$receive]++;}else{$list_post[$receive]=0;}
			$list_post=json_encode($list_post);
			$this->db->query("update list set people=$list_post where owner=$post");
			
			//fresh receiver list
			$list_receive=$this->db->query("select people from list where owner=$receive")->row_array();
			$list_receive=json_decode($list_receive,true);
			if(isset($list_receive[$post])){$list_receive[$post]++;}else{$list_receive[$post]=0;}
			$list_receive=json_encode($list_receive);
			$this->db->query("update list set people=$list_receive where owner=$receive");
			
			//create notice
			$this->db->query("insert into notice(post,receive,type,thing) values($post,$receive,0,$message_id)");
			return true;
		}
		return "Error:不存在的用户";
	}
	
	function get_info($id)
	{
		$res=$this->db->query("select * from message where id=$id")->row_array();
		if(empty($res))
		{
			return "Error:不存在的私信";
		}
		else
		{
			return $res;
		}
	}
}
