
        <nav class="navbar navbar-static-top" role="navigation">
            <a href="/home" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the marginin -->
                <img src="/uploads/admin/{{ $info->logo }}" alt="logo" style="height: 40px;width: 45px;"/>
            </a>
            <!-- Header Navbar: style can be found in header-->
            <!-- Sidebar toggle button-->
            <div>
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button"> <i
                        class="fa fa-fw ti-menu"></i>
                </a>
            </div>
            <style>
                .scrollable-menu {
                height: auto;
                max-height: 340px;
                overflow-x: hidden;
                }
                .dropdown-menu > li > a:hover, .dropdown-menu > li > a:focus {
                text-decoration: none;
                color: #262626;
                background-color: #54c686; 
                }
            </style>
            <div class="navbar-right">
                <ul class="nav navbar-nav">  
                    <!-- User Account: style can be found in dropdown-->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle padding-user" data-toggle="dropdown">
                            <img src="/uploads/admin/{{ $info->logo }}" width="35" class="img-circle img-responsive pull-left"
                                 height="35" alt="User Image">
                            <div class="riot">
                                <div>
                                    {{Auth::user()->first_name .' '. Auth::user()->last_name }}
                                    <span><i class="caret"></i></span>
                                </div>
                            </div>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="/uploads/admin/logo-app.png" class="img-circle" alt="User Image">
                                <p> {{Auth::user()->first_name .' '. Auth::user()->last_name }}</p>
                            </li>  
                            <li role="presentation"></li> 
                            <!-- <li role="presentation" class="divider"></li> -->
                            <!-- Menu Footer-->
                            <li class="user-footer" style="background: #ff3333;"> 
                                <div class="pull-right">
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();" style="color: #fff;">
                                        <i class="fa fa-fw ti-shift-right"></i>
                                        Se déconnecter
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav> 