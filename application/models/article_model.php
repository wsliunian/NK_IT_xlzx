<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//update by L 2012.7.20
//更新内容:
//add exist()
//add post()
//add remove()
//add edit()

//----------------------------函数列表----------------------------
/*
 * exist($id)		determine whether the article exist
 * @id		article id
 * @return 	true|false
 * */
/*
 * post($array)		post article
 * @array	title		article title
 * 			content		article content
 * 			type		article type
 * 			author		article author
 * @return	id|error info
 * */
/*
 * remove($id)		remove article
 * @id		article id
 * @return		true|error info
 * */
/*
 * edit($id,$array)		edit article
 * @id		article id
 * @array		title		article title
 * 				content		article content
 * 				type		article type
 * 				author		article author
 * @return	true|error info
 * */
//---------------------------------------------------------------

class article_model extends CI_model
{
	function exist($id)
	{
		$res=$this->db->query("select id from article where id=$id limit 1")->row_array();
		if(empty($res)){return false;}elss{return true;}
	}
	
	function post($array)
	{
		$title=$this->db->escape($array['title']);
		$res=$this->db->query("select id from article where title=$title limit 1")->row_array();
		if(!empty($res)){return "Error:重复的标题";}
		$content=$this->db->escape($array['content']);
		$type=$array['type'];
		$author=$array['author'];
		
		$this->db->query("insert into article(title,content,type,author) values($title,$content,$type,$author)");
		return $this->db->insert_id();
	}
	
	function remove($id)
	{
		if(!$this->article_model->exist($id)){return "Error:文章不存在";}
		$this->db->query("delete from article where id=$id");
		return true;
	}
	
	function edit($id,$array)
	{
		if(!$this->article_model->exist($id)){return "Error:文章不存在";}
		if(isset($array['title'])){
			$title=$this->db->escape($array['title']);
			$temp=$this->db->query("select id from article where title=$title limit 1")->row_array();
			if(!empty($temp)){return "Error:重复的标题";}
		}else{$title='title';}
		if(isset($array['content'])){$content=$this->db->escape($array['content']);}else{$content='content';}
		if(isset($array['type'])){$type=$array['type'];}else{$type='type';}
		if(isset($array['author'])){$author=$array['author'];}else{$author='author';}

		$this->db->query("update teacher set title=$title,content=$content,type=$type,author=$author where id=$id");
		return true;
	}
}
