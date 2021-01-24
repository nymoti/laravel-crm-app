<aside class="left-side sidebar-offcanvas">
        <!-- sidebar: style can be found in sidebar-->
        <section class="sidebar">
            <div id="menu" role="navigation">
                <div class="nav_profile">
                    <div class="media profile-left">
                        <a class="pull-left profile-thumb" href="#">
                            <img src="/uploads/admin/{{ $info->logo }}" width="54" class="img-circle" alt="User Image"></a>
                        <div class="content-profile">
                            <h4 class="media-heading">                            
                            {{Auth::user()->first_name .' '. Auth::user()->last_name }}
                            </h4>
                            <ul class="icon-list">
                                {{-- <li>
                                    <a href="users.html">
                                        <i class="fa fa-fw ti-user"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="lockscreen.html">
                                        <i class="fa fa-fw ti-lock"></i>
                                    </a>
                                </li> --}}
                                @role('Admin|Super Admin')
                                <li>
                                    <a href="{{ route('getProfilePage') }}">
                                        <i class="fa fa-fw ti-settings"></i>
                                    </a>
                                </li>
                                @endrole
                                <li>
                                    <a href="{{route('logout')}}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                        <i class="fa fa-fw ti-shift-right"></i>
                                    </a>
                                </li>
                                
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </ul>
                        </div>
                    </div>
                </div>
                
                @if(Auth::user()->active === 1)
                <ul class="navigation">
                @role('Admin')
                    <li class="<?php if(isset($dashboard)) echo 'active' ?> " >
                        <a href="{{ route('home') }}">
                            <i class="menu-icon ti-dashboard"></i>
                            <span class="mm-text ">Accueil</span>
                        </a>
                    </li>                     
                    <li class="menu-dropdown <?php if(isset($users_side_bar)) echo 'active' ?> ">
                        <a href="#">
                            <i class="menu-icon ti-user"></i> 
                            <span>Utilisateurs</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li id="<?php if(isset($users_side_bar_newone)) echo 'active' ?>" class="<?php if(isset($users_side_bar_newone)) echo 'active' ?>">
                                <a href="{{route('users.create')}}" >
                                <i class="fa fa-fw ti-user"></i>
                                    Nouveau utilisateur
                                </a>
                            </li>
                            <li id="<?php if(isset($users_side_bar_list)) echo 'active' ?>" class="<?php if(isset($users_side_bar_list)) echo 'active' ?>">
                                <a href="{{route('users.index')}}">
                                <span class="fa fa-fw ti-menu-alt"></span>
                                    Liste des utilisateurs
                                </a>
                            </li>  
                        </ul>
                    </li>
                    <li class="<?php if(isset($undistributed_sheets_side_bar)) echo 'active' ?> " >
                        <a href="{{ route('getUnDistributedSheets') }}"> 
                            <span class="ti-unlink"></span>
                            <span class="mm-text ">Fiches non attribué</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($distributed_sheets_side_bar)) echo 'active' ?> " >
                        <a href="{{ route('getDistributedSheets') }}">
                            <span class="ti-link"></span>
                            <span class="mm-text ">Fiches attribué</span>
                        </a>
                    </li> 
                    
                    <li class="menu-dropdown <?php if(isset($reserved_sheets)) echo 'active' ?> ">
                        <a href="#">
                            <i class="menu-icon ti-clipboard"></i> 
                            <span>Fiches réservation</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li id="<?php if(isset($reserved_sheets_all)) echo 'active' ?>" class="<?php if(isset($reserved_sheets_all)) echo 'active' ?>">
                                <a href="{{route('getReservedSheets')}}" >
                                <span class="ti-bookmark-alt"></span>
                                    Liste réservation
                                </a>
                            </li>   
                            <li id="<?php if(isset($confirmed_sheets)) echo 'active' ?>" class="<?php if(isset($confirmed_sheets)) echo 'active' ?>">
                                <a href="{{route('getConfirmedList')}}" >
                                <span class="ti-check"></span>
                                    Liste réservation confirmé
                                </a>
                            </li>   
                            <li id="<?php if(isset($reserved_sheets_finalstep_side_bar)) echo 'active' ?>" class="<?php if(isset($reserved_sheets_finalstep_side_bar)) echo 'active' ?>">
                                <a href="{{route('getCancelledList')}}" >
                                <span class="ti-close"></span>
                                    Liste réservation annulé
                                </a>
                            </li>  
                        </ul>
                    </li> 
                    

                    <li class="<?php if(isset($all_sheet_side_bar)) echo 'active' ?> " >
                        <a href="{{ route('getAllSheetPage') }}">
                            <span class="ti-files"></span>
                            <span class="mm-text ">Liste des fiches</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($deleted_sheets_side_bar)) echo 'active' ?> " >
                        <a href="{{ route('getDeletedSheets') }}">
                        <span class="ti-trash" style="color: red;"></span>
                        <span class="mm-text ">Fiches supprimés</span>
                        </a>
                    </li>               
                    <li class="menu-dropdown <?php if(isset($statistic_side_bar)) echo 'active' ?> ">
                        <a href="#">
                            <span class="ti-pie-chart"></span>
                            <span>Statistiques</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu"> 
                            <li id="<?php if(isset($stati_agent_side_bar)) echo 'active' ?>" class="<?php if(isset($stati_agent_side_bar)) echo 'active' ?>">
                                <a href="{{route('getGlobalAgentStatisticPage')}}" >
                                <span class="ti-bar-chart"></span>
                                    Les agents
                                </a>
                            </li>
                            <li id="<?php if(isset($stati_closer_side_bar)) echo 'active' ?>" class="<?php if(isset($stati_closer_side_bar)) echo 'active' ?>">
                                <a href="{{route('getCloserGlobalStatisticPage')}}">
                                <span class="ti-bar-chart-alt"></span>
                                    les closers
                                </a>
                            </li> 
                        </ul>
                    </li>
                    <li class="<?php if(isset($calender_side_bar)) echo 'active' ?> " >
                        <a href="{{ route('getCalenderPage') }}">
                        <span class="ti-calendar"></span>
                            <span class="mm-text ">Agenda</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($infos_side_bar)) echo 'active' ?> " >
                        <a href="{{ route('getInfosPage') }}">
                        <span class="ti-settings"></span>
                        <span class="mm-text ">Liste Compaigns</span>
                        </a>
                    </li>  
                @endrole


                @role('Super Admin')
                    <li class="<?php if(isset($dashboard)) echo 'active' ?> " >
                        <a href="{{ route('home') }}">
                            <i class="menu-icon ti-dashboard"></i>
                            <span class="mm-text ">Accueil</span>
                        </a>
                    </li>                     
                    <li class="menu-dropdown <?php if(isset($users_side_bar)) echo 'active' ?> ">
                        <a href="#">
                            <i class="menu-icon ti-user"></i> 
                            <span>Utilisateurs</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li id="<?php if(isset($users_side_bar_newone)) echo 'active' ?>" class="<?php if(isset($users_side_bar_newone)) echo 'active' ?>">
                                <a href="{{route('users.create')}}" >
                                <i class="fa fa-fw ti-user"></i>
                                    Nouveau utilisateur
                                </a>
                            </li>
                            <li id="<?php if(isset($users_side_bar_list)) echo 'active' ?>" class="<?php if(isset($users_side_bar_list)) echo 'active' ?>">
                                <a href="{{route('users.index')}}">
                                <span class="fa fa-fw ti-menu-alt"></span>
                                    Liste des utilisateurs
                                </a>
                            </li> 
                            
                            <li id="<?php if(isset($deleted_users_side_bar_list)) echo 'active' ?>" class="<?php if(isset($deleted_users_side_bar_list)) echo 'active' ?>">
                                <a href="{{route('getDeletedUsers')}}">
                                <i class="fa fa-fw fa-trash-o" style="color:red;"></i>
                                    Utilisateurs désactivé
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="<?php if(isset($undistributed_sheets_side_bar)) echo 'active' ?> " >
                        <a href="{{ route('getUnDistributedSheets') }}"> 
                            <span class="ti-unlink"></span>
                            <span class="mm-text ">Fiches non attribué</span>
                        </a>
                    </li> 
                    <li class="<?php if(isset($distributed_sheets_side_bar)) echo 'active' ?> " >
                        <a href="{{ route('getDistributedSheets') }}">
                            <span class="ti-link"></span>
                            <span class="mm-text ">Fiches attribué</span>
                        </a>
                    </li>   
 
                    <li class="<?php if(isset($all_sheet_side_bar)) echo 'active' ?> " >
                        <a href="{{ route('getAllSheetPage') }}">
                            <span class="ti-files"></span>
                            <span class="mm-text ">Liste des fiches</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($deleted_sheets_side_bar)) echo 'active' ?> " >
                        <a href="{{ route('getDeletedSheets') }}">
                        <span class="ti-trash" style="color: red;"></span>
                        <span class="mm-text ">Fiches supprimés</span>
                        </a>
                    </li>
                    <li class="menu-dropdown <?php if(isset($scripts_side_bar)) echo 'active' ?> ">
                        <a href="#">
                            <i class="menu-icon ti-file"></i> 
                            <span>Les scripts</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li id="<?php if(isset($scripts_side_bar_newone)) echo 'active' ?>" class="<?php if(isset($scripts_side_bar_newone)) echo 'active' ?>">
                                <a href="{{route('scripts.create')}}" >
                                <i class="fa fa-fw fa-plus"></i>
                                    Nouveau Script
                                </a>
                            </li>
                            <li id="<?php if(isset($scripts_side_bar_list)) echo 'active' ?>" class="<?php if(isset($scripts_side_bar_list)) echo 'active' ?>">
                                <a href="{{route('scripts.index')}}">
                                <span class="ti-files"></span>
                                    Liste des scripts
                                </a>
                            </li> 
                        </ul>
                    </li>           
                    <li class="menu-dropdown <?php if(isset($statistic_side_bar)) echo 'active' ?> ">
                        <a href="#">
                            <span class="ti-pie-chart"></span>
                            <span>Statistiques</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu"> 
                            <li id="<?php if(isset($stati_agent_side_bar)) echo 'active' ?>" class="<?php if(isset($stati_agent_side_bar)) echo 'active' ?>">
                                <a href="{{route('getGlobalAgentStatisticPage')}}" >
                                <span class="ti-bar-chart"></span>
                                    Les agents
                                </a>
                            </li>
                            <li id="<?php if(isset($stati_closer_side_bar)) echo 'active' ?>" class="<?php if(isset($stati_closer_side_bar)) echo 'active' ?>">
                                <a href="{{route('getCloserGlobalStatisticPage')}}">
                                <span class="ti-bar-chart-alt"></span>
                                    les closers
                                </a>
                            </li> 
                        </ul>
                    </li>

                    
                    <li class="menu-dropdown <?php if(isset($reserved_sheets)) echo 'active' ?> ">
                        <a href="#">
                            <i class="menu-icon ti-clipboard"></i> 
                            <span>Fiches réservation</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li id="<?php if(isset($reserved_sheets_all)) echo 'active' ?>" class="<?php if(isset($reserved_sheets_all)) echo 'active' ?>">
                                <a href="{{route('getReservedSheets')}}" >
                                <span class="ti-bookmark-alt"></span>
                                    Liste réservation
                                </a>
                            </li>   
                            <li id="<?php if(isset($confirmed_sheets)) echo 'active' ?>" class="<?php if(isset($confirmed_sheets)) echo 'active' ?>">
                                <a href="{{route('getConfirmedList')}}" >
                                <span class="ti-check"></span>
                                    Liste réservation confirmé
                                </a>
                            </li>   
                            <li id="<?php if(isset($cancelled_sheets)) echo 'active' ?>" class="<?php if(isset($cancelled_sheets)) echo 'active' ?>">
                                <a href="{{route('getCancelledList')}}" >
                                <span class="ti-close"></span>
                                    Liste réservation annulé
                                </a>
                            </li>  
                        </ul>
                    </li> 
                    
                    <li class="menu-dropdown <?php if(isset($rserv_form_side_bar)) echo 'active' ?> ">
                        <a href="#">
                            <i class="menu-icon ti-clipboard"></i> 
                            <span>Formulaire de Réservation</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li id="<?php if(isset($reserved_sheets_finalstep_side_bar)) echo 'active' ?>" class="<?php if(isset($reserved_sheets_finalstep_side_bar)) echo 'active' ?>">
                                <a href="{{route('getReservedSheetsFinalStep')}}" >
                                <i class="ti-files"></i>
                                    Liste des fiches
                                </a>
                            </li> 
                            @foreach($categories as $category)
                            <li id="@if(isset($category_side_bar_list))  @if($category->title == $category_side_bar_list ) active @endif @endif" class="@if(isset($category_side_bar_list))  @if($category->title == $category_side_bar_list ) active @endif @endif">
                                <a href="{{route('getSheetCategoryPage', $category->id)}}">                                
                                <i class="fa fa-fw fa-tag"  style="color: #ec4a0b;font-size: 16px;"></i>
                                    {{$category->title}}  ({{count($category->sheets)}})
                                </a>
                            </li> 
                            @endforeach
                            <li >
                                <a  href="#addNewCategory"
                                    data-sfid="addNewCategory"
                                    data-toggle="modal"
                                >
                                <span class="fa fa-fw ti-plus" style="color: #33CC99;"></span>
                                    Ajouter un libellé
                                </a>
                            </li>
                            <li id="<?php if(isset($category_list_side_bar)) echo 'active' ?>" class="<?php if(isset($category_list_side_bar)) echo 'active' ?>">
                                <a href="{{route('getCategoriesList')}}" >
                                <i class="fa fa-fw fa-tags" style="color: #ec4a0b;font-size: 16px;"></i>
                                    Lsite des libellés
                                </a>
                            </li> 
                        </ul>
                    </li> 
                    
                    <li class="<?php if(isset($calender_side_bar)) echo 'active' ?> " >
                        <a href="{{ route('getCalenderPage') }}">
                        <span class="ti-calendar"></span>
                            <span class="mm-text ">Agenda</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($infos_side_bar)) echo 'active' ?> " >
                        <a href="{{ route('getInfosPage') }}">
                        <span class="ti-settings"></span>
                        <span class="mm-text ">Liste Compaigns</span>
                        </a>
                    </li> 
                    <li class="<?php if(isset($setting_side_bar)) echo 'active' ?> " >
                        <a href="{{ route('getSettingPage') }}">
                        <span class="ti-time"></span>
                        <span class="mm-text ">Contrôle d'accès</span>
                        </a>
                    </li> 
                @endrole 

                @role('Agent') 
                    <!-- <li class="<?php //if(isset($dashboard)) echo 'active' ?> " >
                        <a href="/home">
                            <i class="menu-icon ti-dashboard"></i>
                            <span class="mm-text ">Accueil</span>
                        </a>
                    </li>  --> 
                    
                    <li class="menu-dropdown <?php if(isset($sheets_side_bar)) echo 'active' ?> ">
                        <a href="#">
                            <i class="menu-icon ti-file"></i> 
                            <span>Mes fiches</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li id="<?php if(isset($sheets_side_bar_newone)) echo 'active' ?>" class="<?php if(isset($sheets_side_bar_newone)) echo 'active' ?>">
                                <a href="{{route('sheets.create')}}" >
                                <i class="fa fa-fw fa-plus"></i>
                                    Nouveau Fiche
                                </a>
                            </li>
                            <li id="<?php if(isset($sheet_side_bar_list)) echo 'active' ?>" class="<?php if(isset($sheet_side_bar_list)) echo 'active' ?>">
                                <a href="{{route('getAgentsSheets')}}">
                                <span class="ti-list"></span>
                                    Liste des fiches
                                </a>
                            </li>  
                        </ul>
                    </li>

                    
                    <li class="<?php if(isset($scripts_side_bar)) echo 'active' ?> " >
                        <a href="{{ route('getAgentsAndClosersScripts') }}">
                            <i class="menu-icon ti-files"></i>
                            <span class="mm-text ">Liste des scripts</span>
                        </a>
                    </li>           

 
                @endrole
              
                @role('Closer')
                    <li class="<?php if(isset($dashboard)) echo 'active' ?> " >
                        <a href="{{ route('home') }}">
                            <i class="menu-icon ti-dashboard"></i>
                            <span class="mm-text ">Accueil</span>
                        </a>
                    </li>  
                    <li class="<?php if(isset($sheets_side_bar)) echo 'active' ?> ">
                        <a href="{{route('getCloserSheets')}}">
                            <i class="menu-icon ti-files"></i>
                            <span class="mm-text ">Liste des fiches</span>
                        </a>
                    </li> 
                    
                    <li class="<?php if(isset($scripts_side_bar)) echo 'active' ?> " >
                        <a href="{{ route('getAgentsAndClosersScripts') }}">
                            <i class="menu-icon ti-files"></i>
                            <span class="mm-text ">Liste des scripts</span>
                        </a>
                    </li>    
                     
                    <li class="menu-dropdown <?php if(isset($scripts_filter_side_bar)) echo 'active' ?> ">
                        <a href="#">
                            <i class="menu-icon ti-files"></i> 
                            <span>Status</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li id="<?php if(isset($scripts_filter_nrp)) echo 'active' ?>" class="<?php if(isset($scripts_filter_nrp)) echo 'active' ?>">
                                <a href="{{route('getNRPSheets')}}" >
                                <span class="ti-bookmark-alt"></span>
                                    NRP
                                </a>
                            </li> 
                            <li id="<?php if(isset($scripts_filter_refu)) echo 'active' ?>" class="<?php if(isset($scripts_filter_refu)) echo 'active' ?>">
                                <a href="{{route('getRefuSheets')}}">
                                <span class="ti-unlink"></span>
                                    REFUS
                                </a>
                            </li>
                            <li id="<?php if(isset($scripts_filter_rappel)) echo 'active' ?>" class="<?php if(isset($scripts_filter_rappel)) echo 'active' ?>">
                                <a href="{{route('getRappelSheets')}}">
                                <span class="ti-notepad"></span>
                                    RAPPEL
                                </a>
                            </li> 
                        </ul>
                    </li> 
                    <li class="<?php if(isset($calender_side_bar)) echo 'active' ?> " >
                        <a href="{{ route('getCalenderPage') }}">
                        <span class="ti-calendar"></span>
                            <span class="mm-text ">Agenda</span>
                        </a>
                    </li>
              
                @endrole
                </ul>
                <!-- / .navigation --> 
                @endif
            </div>
            <!-- menu --> 
        </section>
        <!-- /.sidebar --> 
    </aside>


    
