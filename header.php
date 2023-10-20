<?php if($user_role === "Patron" )
{
?>    
<section class="menu-section" style="padding-top: 15px; " >
        <div class="navbar">
            <a href="index.php">
                <img class="logo-image" id="logo" src="image/logo1.png" alt="Logo" style="max-height:90px;">
            </a>   
            <div class="container">
                <div class="row ">
                    <div class="col-md-12">
                        <div class="navbar-collapse collapse ">
                            <ul id="menu-top" class="nav navbar-nav navbar-right">
                                <li><a href="dashboard.php" class="menu-top-active">DASHBOARD</a></li>
                                <li><a href="issued-books.php">Received Books</a></li>
                                <li>
                                    <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown"> Account <i class="fa fa-angle-down"></i></a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="my-profile.php">My Profile</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="change-password.php">Change Password</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="logout.php">Log Out</a></li>
                                    </ul>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php } else if($user_role === "Admin" )
{
?>    
<section class="menu-section" style="padding-top: 15px; " >
        <div class="navbar">
            <a href="index.php">
                <img class="logo-image" id="logo" src="image/logo1.png" alt="Logo" style="max-height:90px;">
            </a>   
            <div class="container">
                <div class="row ">
                    <div class="col-md-12">
                        <div class="navbar-collapse collapse ">
                            <ul id="menu-top" class="nav navbar-nav navbar-right">
                                <li><a href="dashboard.php" class="menu-top-active">DASHBOARD</a></li>
                                <li><a href="issued-books.php">Borrowed Books</a></li>
                                <li>
                                    <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown"> Account <i class="fa fa-angle-down"></i></a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="my-profile.php">My Profile</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="change-password.php">Change Password</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="logout.php">Log Out</a></li>
                                    </ul>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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