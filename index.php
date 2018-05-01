
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="assets/css/index.css">
    <link rel="stylesheet" href="vendor/bootstrap/bootstrap.min.css" crossorigin="anonymous">

    <script src="vendor/jquery.slim.js" ></script>
    <script src="vendor/jquery.js"></script>
    <script src="vendor/popper.js" ></script>
    <script src="vendor/bootstrap/bootstrap.min.js"></script>
    <script src="vendor/vuejs/vue.js" ></script>

    <title>Home page</title>
</head>
<body>
<div id="app">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">MyProj</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
        </ul>
    </div>
    <div class="form-inline">
        <input class="form-control mr-sm-2" type="text" placeholder="Search" v-model="keyword"  aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="button" @click="search()">Search</button>
    </div>
    <div class="form-inline dropdown">

        <?php
        session_start();
        if(isset($_SESSION['login_user'])){
            echo '
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                ' . $_SESSION['login_user'] . '
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="profile.php">Profile</a>
                <a class="dropdown-item" href="logout.php">Logout</a>
            </div>';
        }
        else{
            echo '<a class="nav-link" href="login.php">Login</a>';
        }
        ?>




    </div>
</nav>

<div class="container mt-5">
    <button type="button" class="close" aria-label="Close" @click="getBack()">
        <span aria-hidden="true">&times;</span>
    </button>

    <table class="table">
    <thead>
    <tr>
    <th>Name</th>
    <th>Phone</th>
    <th>Age</th>
    <th>Active</th>
    <th>Operations</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="(person,index) in persons">
        <td >{{person.firstName}} {{person.lastName}}</td>
        <td>{{person.phone}}</td>
        <td>{{person.age}}</td>
        <td>{{person.active}}</td>
        <td>
            <button type="button" class="btn btn-primary" @click="removeRow(person.id,index)">delete</button>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter" @click="viewPerson(index)">View </button>
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">{{firstName}} {{lastName}}, {{age}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Phone: {{phone}}</p>
                                <p>Active: {{active}}</p>
                            </div>
                            <div class="modal-body">
                                <h5>Interests: </h5>
                                <p v-for="interest in interests">{{interest.description}}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

        </td>
    </tr>
    </tbody>
    </table>
    </div>
<script src="assets/js/index.js"></script>
</div>
</body> 
</html>