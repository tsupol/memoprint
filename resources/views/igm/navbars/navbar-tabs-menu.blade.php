<div class="vd_navbar vd_nav-width vd_navbar-tabs-menu <?php if (isset($current_navbar)) {echo($current_navbar);} ?> <?php if (isset($current_navbar) && $current_navbar=="vd_navbar-left") {echo($navbar_left_extra_class);} ?> <?php if (isset($current_navbar) && $current_navbar=="vd_navbar-right") {echo($navbar_right_extra_class);} ?>">

	<div class="navbar-menu clearfix">
        <div class="vd_panel-menu hidden-xs">
            {{--<span data-original-title="Expand All" data-toggle="tooltip" data-placement="bottom" data-action="expand-all" class="menu" data-intro="<strong>Expand Button</strong><br/>To expand all menu on left navigation menu." data-step=4 >--}}
                {{--<i class="fa fa-sort-amount-asc"></i>--}}
            {{--</span>                   --}}
        </div>
    	<h3 class="menu-title hide-nav-medium hide-nav-small">Menu</h3>
        <div class="vd_menu">
        	<?php
			  if (isset($current_navbar) && $current_navbar=="vd_navbar-left"){ ?>
				@include("igm/navbars/menu-".$navbar_left_menu)
			  <?php } else if (isset($current_navbar) && $current_navbar=="vd_navbar-right"){ ?>
				@include("igm/navbars/menu-".$navbar_right_menu)
			  <?php }
			?>
        </div>             
    </div>
    <div class="navbar-spacing clearfix">
    </div>
    <div class="vd_menu vd_navbar-bottom-widget">
        <ul>
            <li>
                <a href="pages-logout.php">
                    <span class="menu-icon"><i class="fa fa-sign-out"></i></span>          
                    <span class="menu-text">Logout</span>             
                </a>
                
            </li>
        </ul>
    </div>     
</div>