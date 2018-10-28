<!doctype html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Kolpo Admin</title>
    <meta name="description" content="kolpoBD Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/lib/datatable/dataTables.bootstrap.min.css">
    <!-- <link rel="stylesheet" href="assets/css/bootstrap-select.less"> -->
    <link rel="stylesheet" href="assets/scss/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->

    
</head>
<body>
        <!-- Left Panel -->

        <aside id="left-panel" class="left-panel">
            <nav class="navbar navbar-expand-sm navbar-default">

                <div class="navbar-header">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand" href="./"><img src="images/logo.png" alt="Logo"></a>
                    <a class="navbar-brand hidden" href="./"><img src="images/logo2.png" alt="Logo"></a>
                </div>

                <div id="main-menu" class="main-menu collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class="active">
                            <a href="home.php"> <i class="menu-icon fa fa-dashboard"></i>Dashboard </a>
                        </li>
                        <li>
                            <!-- <a href="new-post.php"> <i class="menu-icon fa fa-th"></i>New Post </a> -->
                            <!-- <a href="stats.php"> <i class="menu-icon fa fa-bar-chart"></i>Stats </a> -->
                            <a href="book-info.php"> <i class="menu-icon fa fa-area-chart"></i>Book Info </a>
                            <a href="add_books.php"> <i class="menu-icon fa fa-area-chart"></i>Add Books </a>
                        </li>

                    </ul>
                </div><!-- /.navbar-collapse -->
            </nav>
        </aside><!-- /#left-panel -->

        <!-- Right Panel -->

        <div id="right-panel" class="right-panel">

            <!-- Header-->
            <header id="header" class="header">

                <div class="header-menu">

                    <div class="col-sm-7">
                        <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>

                    </div>

                    <div class="col-sm-5">
                        <div class="user-area dropdown float-right">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="user-avatar rounded-circle" src="images/admin.jpg" alt="User Avatar">
                            </a>

                            <div class="user-menu dropdown-menu">
                                <a class="nav-link" href="#"><i class="fa fa- user"></i>My Profile</a>
                                <a class="nav-link" href="logout.php"><i class="fa fa-power -off"></i>Logout</a>
                            </div>
                        </div>



                    </div>
                </div>

            </header><!-- /header -->
            <!-- Header-->



        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Dashboard</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="#">Dashboard</a></li>
                            <li><a href="#">Table</a></li>
                            <li class="active">Data table</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <!-- Main row -->
      <div class="row">

<div class="col-xs-12">
  <div class="box">
      <div class="box-header">
        <h3 class="box-title">Data Table With Full Features</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table-bordered table-hover" id="Emptable" class="table table-bordered table-striped">
          <thead class="thead-dark">
          <tr>
            <th>Employee ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Gender </th>
            <th>Phone Number</th>
            <th>Address</th>
            <th>Email ID</th>
            <th>HireDate</th>
            <th>Nationality</th>
            <th>Salary</th>
            <th>Designation</th>
            <th></th>
          </tr>
          </thead>

        </table>
      </div>
  </div>

      <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Update Book Price</h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <form role="form" id="myForm">
        <div class="box-body">
          <div class="form-row">
          <div class="form-group col-md-4">
            <label for="exampleInputEmail1">Book_id</label>
            <input type="email" class="form-control" id="FirstName" placeholder="First Name">
          </div>
          <div class="form-group  col-md-4">
            <label for="exampleInputEmail1">News Print</label>
            <input type="email" class="form-control" id="LastName" placeholder="Last Name">
          </div>
          <div class="form-group  col-md-4">
            <label for="exampleInputEmail1">White Print</label>
            <input type="email" class="form-control" id="LastName" placeholder="Last Name">
          </div>
          </div>
   
        </div>
        </div>
        <!-- /.box-body -->

        <div class="box-footer" style="padding-left: 30px; padding-bottom: 20px;">
          <a  class="btn btn-primary" id="EmpSubmit">Submit</a>
        </div>
      </form>
    </div>
</div>

</div>
      
        </div><!-- .animated -->
    </div><!-- .content -->


    </div><!-- /#right-panel -->

    <!-- Right Panel -->


    <script src="assets/js/vendor/jquery-2.1.4.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/main.js"></script>


    <script src="assets/js/lib/data-table/datatables.min.js"></script>
    <script src="assets/js/lib/data-table/dataTables.bootstrap.min.js"></script>
    <script src="assets/js/lib/data-table/dataTables.buttons.min.js"></script>
    <script src="assets/js/lib/data-table/buttons.bootstrap.min.js"></script>
    <script src="assets/js/lib/data-table/jszip.min.js"></script>
    <script src="assets/js/lib/data-table/pdfmake.min.js"></script>
    <script src="assets/js/lib/data-table/vfs_fonts.js"></script>
    <script src="assets/js/lib/data-table/buttons.html5.min.js"></script>
    <script src="assets/js/lib/data-table/buttons.print.min.js"></script>
    <script src="assets/js/lib/data-table/buttons.colVis.min.js"></script>
    <script src="assets/js/lib/data-table/datatables-init.js"></script>


 
<script type="text/javascript">
$(document).ready(function () {
  // body...

 
  var Employeetable = document.getElementById('Emptable');
  var Submit = document.getElementById('EmpSubmit').addEventListener("click", validate);

  
    var getFromDb="../v1/index.php/book_author_info";
   var Obj;
   xmlhttp = new XMLHttpRequest();

 
  var Emplotable;
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      // console.log(this.responseText);
      Emplotable = this.responseText;
      MakeTable(Emplotable);
   
      // console.log(JSON.parse(Emplotable));
        // document.getElementById("table").innerHTML = this.responseText;
    }
  };
   xmlhttp.open("GET", getFromDb, true);

   xmlhttp.send();

function MakeTable(Emplotable) {
  // body...
  var Empldata = JSON.parse(Emplotable);
  console.log(Empldata);
  var tbody = document.createElement("tbody");

  Employeetable.append(tbody);


  for(var i=0 ; i< Empldata.length ; i++){
      var tr = document.createElement("tr");
      tbody.append(tr);
    
      var td = document.createElement("td");
      td.textContent = Empldata[i].Employee_id;
        tr.append(td);
      var td = document.createElement("td");
            td.textContent = Empldata[i].FirstName;
              tr.append(td);
      var td = document.createElement("td");
            td.textContent = Empldata[i].LastName;
              tr.append(td);
      var td = document.createElement("td");
            td.textContent = Empldata[i].Gender;
              tr.append(td);
      var td = document.createElement("td");
            td.textContent = Empldata[i].PhoneNumber;
              tr.append(td);
      var td = document.createElement("td");
            td.textContent = Empldata[i].Address;
              tr.append(td);

      var td = document.createElement("td");
                  td.textContent = Empldata[i].EmailID;
                    tr.append(td);
      var td = document.createElement("td");
                  td.textContent = Empldata[i].HireDate;
                    tr.append(td);
      var td = document.createElement("td");
                  td.textContent = Empldata[i].Nationality;
                    tr.append(td);

      var td = document.createElement("td");
                  td.textContent = Empldata[i].Salary;
                    tr.append(td);
      var td = document.createElement("td");
                  td.textContent = Empldata[i].Designation;
                    tr.append(td);

      var td = document.createElement("td");
          td.style.cssText = 'display: flex;';

          var butt = document.createElement('button');
          butt.textContent = "Resign";
          butt.classList.add('btn');
          butt.classList.add('btn-danger');
      
          butt.id = 'delete'+Empldata[i].Employee_id;
          td.append(butt);
          
          
          tr.append(td);
          handleclick(Empldata[i].Employee_id);
  }

  $('#Emptable').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : [[ 1, "asc" ]],
      'info'        : true,
      'autoWidth'   : true
    });
}

function handleclick(id) {
  // body...
  document.getElementById("delete"+id).addEventListener("click", function () {
    // body...
    console.log("Clicked");
    console.log("Del Clicked "+ this.parentElement.parentElement.cells[0].innerHTML);

    if(confirm("Are you sure you want to disable all prices in this route?"))
    {  

      var getFromDb="../../v1/index.php?table=DelEmp&Flight="+this.parentElement.parentElement.cells[0].innerHTML;
           xmlhttp = new XMLHttpRequest();

         
          xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              console.log(this.responseText);
              location.reload();
            }
          };
           xmlhttp.open("GET", getFromDb, true);

           xmlhttp.send();
           location.reload();
    }
    else{
      return false;
    }
  });
  
}

function validate() {
  // body...
  console.log("Validation start");
  var FirstName = document.querySelector('#FirstName').value;
  var LastName = document.querySelector('#LastName').value;
  var PhoneNumber = document.querySelector('#PhoneNumber').value;
  var Address = document.querySelector('#Address').value;
  var EmailID = document.querySelector('#EmailID').value;
  var Nationality = document.querySelector('#Nationality').value;
  var Salary = document.querySelector('#Salary').value;
  var Designation = document.getElementById('Designation').textContent;
  // var Gender = document.querySelector('#Male').value;
  var Gender =  $('input[name=optionsRadios]:checked', '#myForm').val();

  var data=new FormData();
  data.append('FirstName',FirstName);
  data.append('LastName',LastName);
  data.append('PhoneNumber',PhoneNumber);
  data.append('Address',Address);
  data.append('EmailID',EmailID);
  data.append('Nationality',Nationality);
  data.append('Salary',Salary);
  data.append('Designation',Designation);
  data.append('Gender',Gender);

  console.log(Gender+Salary);
  var txt = document.getElementById('Designation').textContent;

  if(txt == "Admin"){
     data.append('Password',document.getElementById('Password').value);
    var PosttoDb="../../v1/Admin_insert.php";
    var Obj;
    xmlhttp = new XMLHttpRequest();
   
    xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      console.log("Data sent");
      console.log(this.responseText+"   Admin post   ......");
      location.reload();
    }
   };
   xmlhttp.open("POST", PosttoDb , true);

   xmlhttp.send(data);
    return;
   }
   else if (txt.split(" ")[1] == "Engineer") {

    var PosttoDb="../../v1/Engineer_insert.php";
    var Obj;
    xmlhttp = new XMLHttpRequest();
   
    xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      console.log("Data sent");
      console.log(this.responseText+"   Admin post   ......");
      location.reload();
    }
   };
   xmlhttp.open("POST", PosttoDb , true);

   xmlhttp.send(data);
    return;
   

   }

  //Insert New Passenger
  var PosttoDb="../../v1/Employee_insert.php";
    var Obj;
    xmlhttp = new XMLHttpRequest();
   
  //   var flightresponse;
    xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      console.log("Data sent");
      console.log(this.responseText+"   Posted   ......");
      location.reload();
    }
  };
   xmlhttp.open("POST", PosttoDb , true);

   xmlhttp.send(data);


}


});


</script>




</body>
</html>
