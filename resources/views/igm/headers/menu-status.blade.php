    <ul class="mega-ul">
    <li id="top-menu-2" class="one-icon mega-li"> 
      {{--<a href="index.php" class="mega-link" data-action="click-trigger">--}}
    	{{--<span class="mega-icon"><i class="fa fa-envelope"></i></span> --}}
		{{--<span class="badge vd_bg-red">2</span>        --}}
      {{--</a>--}}
      <div class="vd_mega-menu-content width-xs-3 width-sm-4 width-md-5 width-lg-4 right-xs left-sm" data-action="click-target">
        <div class="child-menu">  
           <div class="title"> 
           	   Messages
               <div class="vd_panel-menu">
                     <span data-original-title="Message Setting" data-toggle="tooltip" data-placement="bottom" class="menu">
                        <i class="fa fa-cog"></i>
                    </span>                                                                              
                </div>
           </div>                 
		   <div class="content-list content-image">
           	   <div  data-rel="scroll">	
               <ul class="list-wrapper pd-lr-10">
                    <li> 
                    		<div class="menu-icon"><img alt="example image" src="{{ asset('/img/avatar/avatar.jpg') }}"></div> 
                            <div class="menu-text"> Do you play or follow any sports?
                            	<div class="menu-info">
                                    <span class="menu-date">12 Minutes Ago </span>                                                                         
                                    <span class="menu-action">
                                        <span class="menu-action-icon" data-original-title="Mark as Unread" data-toggle="tooltip" data-placement="bottom">
                                            <i class="fa fa-eye"></i>
                                        </span>                                                                            
                                    </span>                                
                            	</div>
                            </div> 
                    </li>
                    <li class="warning"> 
                    		<div class="menu-icon"><img alt="example image" src="{{ asset('/img/avatar/avatar-2.jpg') }}"></div>  
                            <div class="menu-text">  Good job mate !
                            	<div class="menu-info">
                                    <span class="menu-date">1 Hour 20 Minutes Ago </span>                                                                         
                                    <span class="menu-action">
                                        <span class="menu-action-icon" data-original-title="Mark as Read" data-toggle="tooltip" data-placement="bottom">
                                            <i class="fa fa-eye-slash"></i>
                                        </span>                                                                            
                                    </span>                                
                            	</div>                            
                            </div> 
                     </li>    
               </ul>
               </div>
               <div class="closing text-center" style="">
               		<a href="#">See All Notifications <i class="fa fa-angle-double-right"></i></a>
               </div>                                                                       
           </div>                              
        </div> <!-- child-menu -->                      
      </div>   <!-- vd_mega-menu-content --> 
    </li>  
    <li id="top-menu-3"  class="one-icon mega-li"> 
      {{--<a href="index.php" class="mega-link" data-action="click-trigger">--}}
    	{{--<span class="mega-icon"><i class="fa fa-globe"></i></span> --}}
		{{--<span class="badge vd_bg-red">2</span>        --}}
      {{--</a>--}}
      <div class="vd_mega-menu-content  width-xs-3 width-sm-4  center-xs-3 left-sm" data-action="click-target">
        <div class="child-menu">  
           <div class="title"> 
           		Notifications 
               <div class="vd_panel-menu">
                     <span data-original-title="Notification Setting" data-toggle="tooltip" data-placement="bottom" class="menu">
                        <i class="fa fa-cog"></i>
                    </span>                   
<!--                     <span class="text-menu" data-original-title="Settings" data-toggle="tooltip" data-placement="bottom">
                        Settings
                    </span> -->                                                              
                </div>
           </div>                 
		   <div class="content-list">	
           	   <div  data-rel="scroll">	
               <ul  class="list-wrapper pd-lr-10">
                    <li> <a href="#"> 
                    		<div class="menu-icon vd_yellow"><i class="fa fa-suitcase"></i></div> 
                            <div class="menu-text"> Someone has give you a surprise 
                            	<div class="menu-info"><span class="menu-date">12 Minutes Ago</span></div>
                            </div> 
                    </a> </li>
                    <li> <a href="#"> 
                    		<div class="menu-icon vd_blue"><i class=" fa fa-user"></i></div> 
                            <div class="menu-text">  Change your user profile details
                            	<div class="menu-info"><span class="menu-date">1 Hour 20 Minutes Ago</span></div>
                            </div> 
                    </a> </li> 
               </ul>
               </div>
               <div class="closing text-center" style="">
               		<a href="#">See All Notifications <i class="fa fa-angle-double-right"></i></a>
               </div>                                                                       
           </div>                              
        </div> <!-- child-menu -->                      
      </div>   <!-- vd_mega-menu-content -->         
    </li>  
     
    <li id="top-menu-profile" class="profile mega-li"> 
        <a href="#" class="mega-link"  data-action="click-trigger"> 
            <span  class="mega-image">
                {{--  memo edit --}}
                {{--<img src="{{ $profile_picture }}" alt="example image" />--}}
            </span>
            <span class="mega-name">
                {{--  memo edit --}}
                {{--{{ $username }} <i class="fa fa-caret-down fa-fw"></i> --}}
            </span>
        </a> 
      <div class="vd_mega-menu-content  width-xs-2  left-xs left-sm" data-action="click-target">
        <div class="child-menu"> 
        	<div class="content-list content-menu">
                <ul class="list-wrapper pd-lr-10">
                    <li> <a href="#"> <div class="menu-icon"><i class=" fa fa-user"></i></div> <div class="menu-text">Edit Profile</div> </a> </li>
                    <li class="line"></li>                
                    <li> <a href="#"> <div class="menu-icon"><i class=" fa fa-cogs"></i></div> <div class="menu-text">Settings</div> </a> </li>
                    <li> <a href="{{ url('auth/logout') }}"> <div class="menu-icon"><i class=" fa fa-sign-out"></i></div>  <div class="menu-text">Sign Out</div> </a> </li>
                    <li class="line"></li>                
                    <li> <a href="#"> <div class="menu-icon"><i class=" fa fa-question-circle"></i></div> <div class="menu-text">Help</div> </a> </li>
                    <li> <a href="#"> <div class="menu-icon"><i class=" glyphicon glyphicon-bullhorn"></i></div> <div class="menu-text">Report a Problem</div> </a> </li>              
                </ul>
            </div> 
        </div> 
      </div>     
  
    </li>       
</ul>
<!-- Head menu search form ends --> 