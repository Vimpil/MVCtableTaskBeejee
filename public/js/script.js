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


var action = 'login';
load_data();

function load_data()
{
	console.log("load_data()");
     
	$.ajax({

          type:"POST",                
          data: {
            action:action,
          },                
          dataType: "html",
          async: false,
          success:function(response){
            //$('#pagination_data').html(response);                                            
            console.log('response');
            console.log(response);
          },
          error:function(){
            alert('error');
          }
     })
   
}


});
