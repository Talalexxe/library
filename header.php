<?php if($user_role === "patron"){ ?>
   
<section class="menu-section" style="padding-top: 15px; " >
        <div class="navbar">
            <a href="dashboard.php">
                <img class="logo-image" id="logo" src="image/logo1.png" alt="Logo" style="max-height:90px;">
            </a>   
            <div class="container">
                <div class="row ">
                    <div class="col-md-12">
                        <div class="navbar-collapse collapse ">
                            <ul id="menu-top" class="nav navbar-nav navbar-right">
                                <li><a href="user-dashboard.php">DASHBOARD</a></li>
                                <li><a href="books">Book Listing</a></li>
                                <li><a href="return">Borrowed Books</a></li>
                                <li>
                                    <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown"> Account <i class="fa fa-angle-down"></i></a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:void(0);p" onclick="confirmLogout()">Log Out</a></li>
                                    </ul>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
        <!-- Log Out Script -->
        <script>
        function confirmLogout() {
            var result = confirm("Are you sure you want to log out?");
            if (result) {
                // If the user confirms, redirect to logout.php
                window.location.href = "logout.php?logout=true";
            } else {
                // If the user cancels, close the dropdown and do nothing else
                var dropdown = document.getElementById("myDropdown");
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        }
    </script>

    <?php } else if($user_role === "admin" )
{
?>    
<section class="menu-section" style="padding-top: 15px; " >
        <div class="navbar">
            <a href="admin-dashboard.php">
                <img class="logo-image" id="logo" src="image/logo1.png" alt="Logo" style="max-height:90px;">
            </a>   
            <div class="container">
                <div class="row ">
                    <div class="col-md-12">
                        <div class="navbar-collapse collapse ">
                            <ul id="menu-top" class="nav navbar-nav navbar-right">                            
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="books.php">Manage Books</a></li>
                               <!-- <li role="presentation"><a role="menuitem" tabindex="-1" href="manage-users.php">Manage Users</a></li>-->
                                <li>
                                    <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown"> Account <i class="fa fa-angle-down"></i></a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:void(0);p" onclick="confirmLogout()">Log Out</a></li>
                                    </ul>
                                </li>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <!-- Log Out Script -->
    <script>
        function confirmLogout() {
            var result = confirm("Are you sure you want to log out?");
            if (result) {
                // If the user confirms, redirect to logout.php
                window.location.href = "logout.php?logout=true";
            } else {
                // If the user cancels, close the dropdown and do nothing else
                var dropdown = document.getElementById("myDropdown");
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        }
    </script>

    </section>
    <?php } else { ?>
        <section class="menu-section"style="padding-top: 15px; ">
            <div class="container">
                <div class="row ">
                    <div class="col-md-12">
                        <div class="navbar-collapse collapse ">
                            <ul id="menu-top" class="nav navbar-nav navbar-right">      
                                <li><a href="index.php">Home</a></li>
                                <li><a href="login.php">Login</a></li>
                                <li><a href="registration.php">User Signup</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    <?php } ?>