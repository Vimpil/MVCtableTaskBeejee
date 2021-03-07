$(document).ready(function(){


/* If user not admin, thats usual user */
if(window.role_id!=1){
  window.role_id=0;
}


/* Usual user cant change table */
if(window.role_id==0){
  $(document).on('click', '#myTable', function(){
    return false;
  })  
}


// Multi windows logout
window.addEventListener('storage', function(event){
    if (event.key == 'logout-event') {     
    window.location = window.location.pathname;    
    }
});

// Clear html tags output
function escapeHtml(text) {
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };

  return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}



/* Logout button */
$(document).on('click', '#adminLogoutBtn', function(e){
    var action = 'logout';    
    logout();
    function logout()
    {
      
         $.ajax({
              type:"POST",                
              data: {
                action:action,
              },                
              dataType: "html",
              async: false,
              success:function(response){
              window.location = window.location.pathname;
                localStorage.setItem('logout-event', 'logout' + Math.random());                   
              },
              error:function(){

              }
         })

    }
});


// Toggle boolean
function toggleFlag(value){
   var toggle = value ? false : true;
   
   return toggle;
}

// Boolean to ASC/DESC
function boolToOrder(value){
  if(value){
    toggle='ASC';
   }else{
    toggle='DESC';
   }
   return toggle;
}

// Default page
var page = 1;

// ASC/DESC table values boolean
var tablesSortOrders=[true,true,true,true,true];
window.ascDesc = 'ASC';

// Current th order column variable
window.tableHead = 'id';


// Th Click order column
$(document).on('click', '#myTable th', function(e){
console.log("column");
  window.tableHead=($(this).attr('name'));

  switch (window.tableHead) {

    case 'id':
      tablesSortOrders[0]=toggleFlag(tablesSortOrders[0]);
      window.ascDesc=boolToOrder(tablesSortOrders[0]);
      break;
    case 'name':    
      tablesSortOrders[1]=toggleFlag(tablesSortOrders[1]);
      window.ascDesc=boolToOrder(tablesSortOrders[1]);
      break;
    case 'email':
      tablesSortOrders[2]=toggleFlag(tablesSortOrders[2]);
      window.ascDesc=boolToOrder(tablesSortOrders[2]);
      break;
    case 'task':
      tablesSortOrders[3]=toggleFlag(tablesSortOrders[3]);
      window.ascDesc=boolToOrder(tablesSortOrders[3]);
      break;
    case 'status':
      tablesSortOrders[4]=toggleFlag(tablesSortOrders[4]);
      window.ascDesc=boolToOrder(tablesSortOrders[4]);
      break;

  }


  load_data(page, window.tableHead, window.ascDesc);

});

// Add row
$(document).on('click', '#addBtn', function(e){
e.preventDefault();
var validity=true;

if(!$('#email')[0].checkValidity()){
  $('#email').val("");
  $('#email').attr("placeholder","Please enter valid e-mail address");
  validity=false;  
}

if(!$('#name')[0].checkValidity()){
  $('#name').attr("placeholder","Please enter your name");
  validity=false;
}

if(!$('#task')[0].checkValidity()){
  $('#task').attr("placeholder","Please enter your task");
  validity=false;
}

if(validity){


    var action = 'addTask';
    var name=$('#name').val();
    var email=$('#email').val();
    var task=$('#task').val();    

    add_data(name, email, task);

    function add_data(name, email, task)
    {
      
         $.ajax({
              type:"POST",                
              data: {
                action:action, name:name, email:email, task:task,
              },                
              dataType: "html",
              async: false,
              success:function(response){
                   alert('Your task successfully added!')
              },
              error:function(){
                alert('error');
              }
         })

         load_data(page, window.tableHead, window.ascDesc);

    }
  }

  });


// Update row
$(document).on('click', '#updateBtn', function(){

    var action = 'updateRowTask';
    
    var id = $('#choosed_row').text().match(/\d+/)[0];
    task=$('#task').val();
    update_row_task(id,task);

    function update_row_task(id, task)

    {
      
        $.ajax({
              type:"POST",                
              data: {
                action:action, id:id, task:task
              },                
              dataType: "html",
              async: false,
              success:function(response){
                window.currentSelTask.text(task);                
              },
              error:function(){
                alert('error');
                
              }
        })
        
        load_data(page, window.tableHead, window.ascDesc);
        
        $("#myTable").find('tr').each(function (i) { 
            var $fieldset = $(this);
            if($(this).find(">:first-child").text()==id){
                window.choosen = $fieldset;                      
            }
        });        
        window.choosen.addClass('choosen');

    }
}); 


// Change page
var action = 'changeTablePage';
load_data(window.page,window.tableHead,window.ascDesc);


function load_data(page, tableHead, ascDesc)
{
  
     $.ajax({

          type:"POST",                
          data: {
            action:action, page:page, tableHead:tableHead, ascDesc:ascDesc
          },                
          dataType: "html",
          async: false,
          success:function(response){			  
            
            
            $('#pagination_data').html(response);                                            
          },
          error:function(){
            alert('error');
          }
     })
}

// Change page link click    
$(document).on('click', '.pagination_link', function(){
  
  page = $(this).attr("id");
  load_data(page, window.tableHead, window.ascDesc);
  
  $('#choosed_row').text('id: non selected for edit');

  $('#task').val('');
  
  $('#addBtn').prop('disabled', false);
  $('#updateBtn').prop('disabled', true);

});

  
  

// Change page link click
$(document).on('click', '#pagination_data tr:not(:first-child)', function(e){
    console.log("#pagination_data tr:not(:first-child)");
    
    if(window.role_id==1){

      var choosenClass = $(this).parent().find('.choosen');

      $nearest_td = $(e.target).closest('td');

      $row = $nearest_td.parent();


        if(choosenClass&&(!(choosenClass).is($(this)))){
          
          task=$row.find('td').eq(3).text()

          if(!($(e.target).closest('input').length>0)){
            choosenClass.removeClass('choosen');        
            $('#task').val(task);
          }

          id=$row.find('td').first().text();
          window.currentSelTask=$row.find('td').eq(3);
          ;
      

        }else{
	
          idString='non selected for edit';
          $('#task').val(task);
          
          $('#addBtn').prop('disabled', false);
          $('#updateBtn').prop('disabled', true);
        }
      
        if(!($(e.target).closest('input').length>0)){

          var choosenClass = $(this).parent().find('.choosen');
          if(!(choosenClass.length>0)){
            $('#task').val(task);
            $('#addBtn').prop('disabled', true);
          $('#updateBtn').prop('disabled', false);

          }else{

            $('#task').val('');
            
            $('#addBtn').prop('disabled', false);
            $('#updateBtn').prop('disabled', true);

          }

          $(this).toggleClass('choosen');
          
          $('#choosed_row').text('id: '+id);
        }

        $input_td = $(e.target);
        console.log('$input_td '+JSON.stringify($input_td));
        $row = $input_td.parent().parent();

        if($input_td.length>0){

          var action = 'updateRowStatus';

          var status=$row.find('td:last-child').find('input:checked').val();

          if(!status){
            status=0;
          }else{
            status=1;
          }

          rowStatusUpdate();
          console.log("id "+idString+"status "+status);
          function rowStatusUpdate(page)
            {
          
             $.ajax({
                  type:"POST",                
                  data: {
                    action:action, id:id, status:status
                  },                
                  dataType: "html",
                  async: false,
                  success:function(response){                                     
                  },
                  error:function(){
                    alert('error updateRowStatus');
                  }
             })
            }
      

          }

      }else{
         $('#choosed_row').text(' ');
      }
  });
});
