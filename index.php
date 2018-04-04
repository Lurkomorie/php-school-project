<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="assets/css/index.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" ></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" ></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <title>Home page</title>
</head>


<body class="container">
<form class="pure-form">
<div style="font-size:50px">All users:</div>
<fieldset style="font-size:25px" >
<input type="text" name="search" placeholder="Search">
<button type="submit" class="btn">Search</button>
</fieldset>
<div id="UserList"> 

    <div id="UserList">
    <table class="table" style="font-size:25px">
    <thead>
    <tr>
    <th>Id</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Age</th>
    <th>Active</th>
    <th>Operations</th>
    </tr>
    </thead>
    <tbody>
    
    </tbody>
    <script>
    $.getJSON("http://localhost:80/php-school-project/services.php?q=viewAllP", function(){
    console.log( "Success" );
    }).done(function(data){
    var html ="";
    for (var user_index = 0; user_index < data.length; user_index++) {
    html += "<tr>";
    html += "<td>" + data[user_index].id + "</td>";
    html += "<td>" + data[user_index].firstName + " " + data[user_index].lastName + "</td>";
    html += "<td>" + data[user_index].phone + "</td>";
    html += "<td>" + data[user_index].age + "</td>";
    html += "<td>" + data[user_index].active + "</td>";
    html += "<td><button class='btn' data-id=\""+data[user_index].id+"\">Edit</button> <button id='delete' class='btn delete' data-id=\""+data[user_index].id+"\">Delete</button></td>"; 
    html += "</tr>";
    }
    $('table tbody').html(html);
    }).fail(function(){
    console.log( "Error" );
    alert("Error");
    });
    $(document).on("click", "td button.delete", function(event) {
    event.preventDefault();
    var client_id = $(this).attr("data-id");
    var server_url = "http://localhost:80/php-school-project/services.php?q=deleteP&p=" + client_id;
    $(this).parents('tr').remove();
    console.log(client_id);
    $.ajax(server_url, function(){
    console.log( "Success" );
    }).done(function(){
    });
    });
    </script>
    </table>
    <div class="pagination" style="font-size: 40px;">
    <a href="#">&laquo;</a>
    <a href="#" v-for="page in pages" v-on:click="count()">{{page}}</a>
    <a href="#">&raquo;</a>
    </div>
    </div> 
</form>
</body> 
</html>