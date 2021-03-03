<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\TableRows;
/**
 * Home controller
 *
 * PHP version 7.0
 */
class Table extends \Core\Controller
{


	/**
     * Show the index page
     *
     * @return void
     */
    
    
    function changeTablePage(){

		$record_per_page = 3;  
		$page = '';  
		$output = '';
		$total_records = '';
		$res = [];
		$query = '';
		$status='';
		$ascDesc=!empty($_POST['ascDesc']) ? trim($_POST['ascDesc']) : null;
		
		if(isset($_POST["page"]))  
		 {  
		      $page = $_POST["page"];  
		 }  
		 else  
		 {  
		      $page = 1;  
		 }


		$start_from = ($page - 1)*$record_per_page;



 		if(isset($_POST["tableHead"])){
 			$tableHead = $_POST["tableHead"];
 		}else{
 			$tableHead = 'id';
 		}

		
		$tableRows = TableRows::getTablePage($tableHead,$ascDesc, $start_from, $record_per_page);
		$total_records = count(TableRows::getAllTable());
			
		$output .= "  
			<table id='myTable'>
	        <tbody style='width:100%>
	        <tr>
	        	<th id='idClick' name='id' style='display:none'></th>
              	<th id='nameClick' name='name' style='width:30%'>пользователь</th>
              	<th id='emailClick' name='email' style='width:20%'>email</th>
              	<th id='taskClick' name='task' style='width:40%'>текст задачи</th>
              	<th id='statusClick' name='status' style='width:10%'>статус</th>
           </tr>  
		";  
	
		foreach($tableRows as $row) {
			
			if(!empty($_POST['editedTask']) ? trim($_POST['editedTask']) : null==1){
				$editedTask='<div class = "editedTask">edited</div>';
			}else{
				$editedTask='';
			}

			if($row["status"]==0){
				$status='<input type="checkbox"> '.$editedTask;
			}else{
				$status='<input type="checkbox" checked>'.$editedTask;
			};

			 $output .= '  
			           <tr>  
			                <td style="display:none">'.$row["id"].'</td> 
			                <td>'.htmlspecialchars($row["name"]).'</td>  
			                 <td>'.htmlspecialchars($row["email"]).'</td>  
			                <td>'.htmlspecialchars($row["task"]).'</td>  
			                 <td>'.$status.'</td>  
			           </tr>  
			      ';  
		}
		/*
		$query = "SELECT COUNT(IFNULL(id, 1)) FROM tasks;";
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		*/
		/*
		while ($row=$stmt->fetch())
		
		{	
			 $total_records=$row[0];
		}
		*/
		
		$output .= '</table><br /><div align="center" id="table_pages">';
		$total_pages = ceil($total_records/$record_per_page);
		for($i=1; $i<=$total_pages; $i++)  
		 {  
		 	if ($i==$page){
				$output .= "<span class='pagination_link active' style='cursor:pointer; padding:6px; border:1px solid #ccc;' id='".$i."'>".$i."</span>";
		 	}else{

		      $output .= "<span class='pagination_link' style='cursor:pointer; padding:6px; border:1px solid #ccc;' id='".$i."'>".$i."</span>";  
		  }
		 } 

		$res = $output;

	    print_r($res);

  	}
  	
    public function addTask() {
		/*$_SESSION['role_id'] = 3;		 
		 $res = "<h1>HEEE</h1>";
		 echo ($res);*/
		 $total_records = (TableRows::getAllTable());
		 $lastId=[];

		foreach($total_records as $row) {
		    
		    $id = $row['id'];
		    $name = $row['name'];
		    $email = $row['email'];
		    $task = $row['task'];
		    

		    $lastId = array("id" => $id,"name" => $name,"email" => $email,"task" => $task);
		    
		    
		}


		$_SESSION['addRow']=$lastId;

			$name = $_POST['name'];
			$email = $_POST['email'];
			$task = $_POST['task'];
			
			$status = 0;
			$newId=$lastId['id']+1;
			$tableRows = TableRows::insterTable($newId,$name, $email, $task, $status);
		 
	}
	
    
    public function indexAction()
    {
		if(!empty($_POST)) {
			$action = $_POST['action'];
			
			
			switch ($action) {


				case 'pageChange':
				
					if(!$this->pageChange()) {
						
					}
				
					break;


				case 'logout':
				
					if(!$this->logout()) {
						
					}
				
					break;


				case 'addTask':

					if(!$this->addTask()) {
						
					}
					break;


				case 'changeTablePage':

					if(!$this->changeTablePage()) {
						
					}
					break;

				case 'updateRowStatus':

				if(!$this->updateRowStatus()) {
						
					}
					break;

				case 'updateRowTask':

				if(!$this->updateRowTask()) {
						
					}
					break;				


			}
		}else{
		
		
		if(empty($startFrom)){
				$startFrom=0;
				}
				
		if(empty($pageStep)){
				$pageStep=3;
				}

		if(empty($pageNum)){
				$pageNum=1;
				} 
		
		if(isset($_POST['login'])){
			//Retrieve the field values from our login form.
            
            $username = !empty($_POST['login']) ? trim($_POST['login']) : null;
            $passwordAttempt = !empty($_POST['password']) ? trim($_POST['password']) : null;
            $passwordAttempt = md5($passwordAttempt);
            
            $user = User::getUser($username, $passwordAttempt);
            
            if($user == false){
            
				$errMsg = "User $username not found.";
				
				View::renderTemplate('Table/index.html', [
				'role_id'  => 0,
				'error'    => $errMsg,
				'output'   => $output,
				'pageNum'  => $pageNum
				]);
				
			}else{
				/*
				View::renderTemplate('Table/index.html', [
					'role_id'  => 1,
					'output'   => ($user["index"])
					]);
					
				*/
				if($passwordAttempt == $user["password"]) {
					
					View::renderTemplate('Table/index.html', [
					'role_id'  => 1,
					'output'   => $output,
					'pageNum'  => $pageNum
					]);
				
				}else{
				$errMsg = 'Password did not match!';
				View::renderTemplate('Table/index.html', [
					'role_id'  => 0,
					'error'    => $errMsg,
					'output'   => $output,
					'pageNum'  => $pageNum
				]);
				}
			}
            
		}else{
		
			View::renderTemplate('Table/index.html', [
			'title'    => 'Список задач',
			'role_id'  => 0,    
			//'output'   => $output,	
			'pageNum'  => $pageNum
			]);
			
		}
	}
}
	


		
}


