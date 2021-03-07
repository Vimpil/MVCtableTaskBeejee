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


session_start();
class Table extends \Core\Controller
{


	/**
     * Show the index page
     *
     * @return void
     */
    
    function debug_to_console($data) {
		$output = $data;
		if (is_array($output))
			$output = implode(',', $output);

		echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
	}

//$this->debug_to_console("Test");
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
		 
		$total_records=(TableRows::countTable());
		
		$some="some";
		
		
		$output .= "  
			<table id='myTable' class='fixed'>
	        <tbody>
	        <tr>
	        	<th id='idClick' name='id' style='display:none'></th>
              	<th id='nameClick' name='name'>пользователь</th>
              	<th id='emailClick' name='email'>email</th>
              	<th id='taskClick' name='task'>текст задачи</th>
              	<th id='statusClick' name='status'>статус</th>
           </tr>  
		";  
	
		foreach($tableRows as $row) {
			
			if($row["editedTask"]==1){
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
		$SESSION['output']=$output;
		$res = $output;

	    print_r($res);
	    
	    

  	}
  	
    public function addTask() {
		
		 $total_records = (TableRows::countTable());
		 $lastId=[];
	
		if($total_records!=0){
			
			$newId=$total_records+1;
			
		}else{
			$newId=1;
		}
		
		//debug_to_console('$total_records '+$total_records);
//$newId=1;
			$name = $_POST['name'];
			$email = $_POST['email'];
			$task = $_POST['task'];			
			$status = 0;
			
			TableRows::insterTable($newId,$name, $email, $task, $status);
			
		 
	}
	
	function updateRowStatus(){

  		$id = $_POST['id'];  
  		$status = $_POST['status'];  

		TableRows::updateRowStatus($id, $status);		
		

  	}
  	
  	function updateRowTask(){
  		
  		
  		$id = $_POST['id'];  
  		$task = $_POST['task'];  
/*
		debug_to_console('$id');
		debug_to_console($id);
		debug_to_console('$task');
		debug_to_console($task);
*/
		TableRows::updateRowTask($id, $task);		
		

  	}
  	
  	function login(){
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
				/*'output'   => $output,
				'pageNum'  => $pageNum*/
				]);
				
			}else{		
				if($passwordAttempt == $user["password"]) {
					
					View::renderTemplate('Table/index.html', [
					'role_id'  => 1,
					/*'output'   => $output,
					'pageNum'  => $pageNum*/
					]);
					$_SESSION['role_id'] = 1;
				
				}else{
				$errMsg = 'Password did not match!';
				View::renderTemplate('Table/index.html', [
					'role_id'  => 0,
					'error'    => $errMsg,
					/*'output'   => $output,
					'pageNum'  => $pageNum*/
				]);
				}
			}
		}
		
		
		function logout(){
			$_SESSION['role_id'] = 0;
			 session_destroy();
		}
	
    
    public function indexAction()
    {
		if(!isset($_SESSION['role_id'])){
			$_SESSION['role_id']=0;
			}
		
		
		if(!empty($_POST)) {
			$action = $_POST['action'];
			
			
			switch ($action) {


				case 'pageChange':
				
					if(!$this->pageChange()) {
						
					}
				
					break;


				case 'login':
				
					if(!$this->login()) {
						
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
		
			 
			if($_SESSION['role_id'] == 1){
				
				View::renderTemplate('Table/index.html', [
				'title'    => 'Список задач',
				'role_id'  => 1,    	
				'pageNum'  => $pageNum,
				//'output'   => $this->changeTablePage($output),
				]);
				
				
				}else{
            
		
			View::renderTemplate('Table/index.html', [
			'title'    => 'Список задач',
			'role_id'  => 0,    	
			'pageNum'  => $pageNum,
			//'output'   => $this->changeTablePage($output),
			]);
			
		}
			
	}
}
	


		
}


