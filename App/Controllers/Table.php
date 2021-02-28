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
    
    
    public function changeTablePage($startFrom, $pageStep){
		    
		    $output = "";
			
			$output .= "<table id='myTable'>
	        <tbody style='width:100%>
	        <tr>
	        	<th id='idClick' name='id' style='display:none'></th>
              	<th id='nameClick' name='name' style='width:30%'>пользователь</th>
              	<th id='emailClick' name='email' style='width:20%'>email</th>
              	<th id='taskClick' name='task' style='width:40%'>текст задачи</th>
              	<th id='statusClick' name='status' style='width:10%'>статус</th>
           </tr>  ";  
			$tableRows = TableRows::getAllTable();
			$rowLength = count($tableRows);
			$finishTo = 0;
			if($startFrom+$pageStep>$rowLength){
				$finishTo = $rowLength;
			}else{
				$finishTo=$startFrom+$pageStep;
			}
			for($i=$startFrom;$i<$finishTo;$i++){
				
				$output .= '  
			           <tr>  
			                <td style="display:none">'.$tableRows[$i]["id"].'</td> 
			                <td>'.htmlspecialchars($tableRows[$i]["name"]).'</td>  
			                 <td>'.htmlspecialchars($tableRows[$i]["email"]).'</td>  
			                <td>'.htmlspecialchars($tableRows[$i]["task"]).'</td>  
			                <td>'.htmlspecialchars($tableRows[$i]["status"]).'</td>  
			           </tr>  
			      ';  
				
				}
			$links="";
			
			$pagesCount=ceil($rowLength/$pageStep);
			$output .= '</table><div class="links block">';
			
			/*
			for($page = 1; $page<= $pagesCount; $page++) {  
				$output .= '<a href = "index2.php?page=' . $page . '">' . $page . ' </a>';  
			}
			*/
			//<input type="button" name="action" value="pageChange" />
			for($page = 1; $page<= $pagesCount; $page++) {  
				$output .= '<form method="post" class="form-signin" name="form-signin" action="index">
        <input type="hidden" name="action" class="form-control" value="pageChagnge">
               <input type="submit" name="pageChagnge"  class="btn btn-lg btn-primary btn-block" value="'.$page.'"/>
        </form>  ';
			}
        
        

			$output .= '</div>';
			
			/*foreach($tableRows as $row) {
				$output .= '  
			           <tr>  
			                <td style="display:none">'.$row["id"].'</td> 
			                <td>'.htmlspecialchars($row["name"]).'</td>  
			                 <td>'.htmlspecialchars($row["email"]).'</td>  
			                <td>'.htmlspecialchars($row["task"]).'</td>  
			                <td>'.htmlspecialchars($row["status"]).'</td>  
			           </tr>  
			      ';  
				}*/
	  
	  return array('output' => $output,'rowLength' => $rowLength);
	}
    
    public function indexAction()
    {
		if(empty($startFrom)){
				$startFrom=0;
				}
				
		if(empty($pageStep)){
				$pageStep=3;
				}

		if(empty($pageNum)){
				$pageNum=1;
				}


		$changeTablePageArray=($this->changeTablePage($startFrom, $pageStep));				
		$output=$changeTablePageArray['output'];
		//$rowLength=$changeTablePageArray['rowLength'];
		
		
		
		/*
		foreach($changeTablePageArray as $key => $value)
		{
		  if($key=='output'){
			  $output=$value;
		  }
		  if($key=='rowLength'){
			  $rowLength=$value;
		  }
		}
		*/
		
		//$pages_count=($this->changeTablePage($startFrom, $pageStep));
		
		
		
        if(isset($_POST['pageChagnge'])){
			$pageNum = !empty($_POST['pageChagnge']) ? trim($_POST['pageChagnge']) : null;
			View::renderTemplate('Table/index.html', [
				'role_id'  => 0,
				'output'   => $output,
				'pageNum'  => $pageNum
				]);
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
					]);
				
				}else{
				$errMsg = 'Password did not match!';
				View::renderTemplate('Table/index.html', [
					'role_id'  => 0,
					'error'    => $errMsg,
					'output'   => $output,
				]);
				}
			}
            
		}else{
		
			View::renderTemplate('Table/index.html', [
			'title'    => 'Список задач',
			'role_id'  => 0,    
			'output'   => $output,	
			]);
			
		}
	}
	
	
	public function loginAction()
    {		
		
		
		
			
	}   


		
}


