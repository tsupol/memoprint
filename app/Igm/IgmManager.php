<?php namespace App\Igm;

use App\UserToken;
use DB;
use Instagram;
use Session;
use Auth;
use Redirect;

class IgmManager {

    public function doSomething()
    {
        echo 'Doing something!';
    }

    public function getTemplateData() {
        /*  SPECIFIC PAGE SETTING  */

        /*  GENERAL SETTING  */
        $data['author'] = 'Venmond'; // for author meta data: <meta name="author" content="$data['author']; ">
        $data['website_name'] = 'IGM'; // for closing title every page: <title>$data['title'];  | $data['website_name'];</title>
        $data['footer_message'] = 'Copyright &copy; 2014 Secret Ingredients Inc. All Rights Reserved'; // Set Copyright message on footer
        $data['logo_path'] = 'img/logo-memo.png';
        $data['background'] = ''; // '' = none, or 'background-login', 'vd_bg-grey', 'background-1' until 'background-8' or create your own background css

        /*  LAYOUT SETTING  */

        $data['responsive'] = 1; // 1= Responsive, 0 = Non Responsive
        $data['width_non_responsive'] = 1170; // Width for middle or boxed layout with non responsive ( if $data['responsive'] set to 0)
        $data['layout'] = 'full-layout'; // 'full-layout', 'middle-layout', 'boxed-layout'
        $data['navbar_top_fixed'] = 1; // 1 = Top Navigation bar follow when scroll down, 0 = normal

        /*  HEADER AND FOOTER SETTING  */

        $data['header'] = 'header-default'; // 'header-1' or 'header-2' or 'header-3' or 'header-4' ,or other 'header-*' located at igm/headers/$data['header'].tpl.php
        $data['footer'] = 'footer-default'; // 'footer-1' or 'footer-2' or 'footer-3' or 'footer-4' ,or other 'header-*' located at igm/footers/$data['footer'].tpl.php

        /*  HEAD SECTION PANEL MENU SETTING  */

        $data['remove_navbar'] = 1; // Remove Navbar Toggle Button, 1 = show, 0 = hide
        $data['remove_header'] = 1; // Remove Header Toggle Button, 1 = show, 0 = hide
        $data['fullscreen'] = 1; // Full Screen Toggle Button, 1 = show, 0 = hide
        $data['boxedtofull'] = 0; // Boxed to Full Layout Toggle Button, 1 = show, 0 = hide

        /*  LEFT SIDEBAR NAVIGATION SETTING  */

        $data['navbar_left_config'] = 1; // 2 = Available but Start Invisible, 1 = Available Start Visible, 0 = Not available
        $data['navbar_left'] = 'navbar-tabs-menu'; // 'navbar-tabs-menu' or 'navbar-tabs-profile' or 'navbar-tabs-tab' or 'navbar-email' or 'navbar-chat' or 'navbar-no-tab'
        $data['navbar_left_start_width'] = ''; // '' = default width, 'nav-left-medium' = medium width, 'nav-left-small' = small width
        $data['navbar_left_fixed'] = 0; // Navbar left: 1 = fixed position , 0 = normal, fixed position compatible with no footer layout
        $data['navbar_left_menu'] = 'default'; // located at igm/navbars/menu- $data['navbar_left_menu'] .tpl.php
        $data['navbar_left_extra_class'] ='';
        $data['current_navbar'] = 'vd_navbar-left';

        /*  RIGHT SIDEBAR NAVIGATION SETTING  */

        $data['navbar_right_config'] = 2; // 2 = Available but Start Invisible, 1 = Available Start Visible, 0 = Not available
        $data['navbar_right'] = 'navbar-chat'; // 'navbar-tabs-menu' or 'navbar-tabs-profile' or 'navbar-tabs-tab' or 'navbar-email' or 'navbar-chat' or 'navbar-no-tab'
        $data['navbar_right_start_width'] = ''; // '' = default width, 'nav-right-medium' = medium width, 'nav-right-small' = small width
        $data['navbar_right_fixed'] = 0; // Condition: for right aligned navbar, $data['layout'] must be 'full-layout',  1 = fixed position , 0 = normal, compatible with no footer layout
        $data['navbar_right_menu'] = 'default'; // located at igm/navbars/menu- $data['navbar_right_menu'] .tpl.php
        $data['navbar_right_extra_class'] ='';

        /*  SIDEBAR MENU TOGGLE BUTTON SETTING  */

        $data['medium_nav_toggle'] = 1; // visibility of change sidebar menu width from large to medium button: 1= visible, 0 = none
        $data['small_nav_toggle'] = 1; // visibility of change sidebar menu width from large to small or medium to small button: 1= visible, 0 = none
        $data['enlarge_left_to_medium'] = '0'; // if small_nav_toggle clicked, enlarge left sidebar menu to: '1' = medium, '0' default/large;
        $data['enlarge_right_to_medium'] = '0'; // if small_nav_toggle clicked, enlarge right sidebar menu to '1' = medium, '0' default/large;

        /*  EXTRA CLASS SETTING  */

        $data['top_menu_extra_class'] = '';  // background class such as 'vd_bg-white', also 'light-top-menu', 'menu-search-style-2'
        $data['logo_container_extra_class'] =''; // background class such as 'vd_bg-green', also 'panel-menu-style-2'
        $data['bottom_extra_class'] = ''; // located inside footer <div class="vd_bottom $data['bottom_extra_class']"> value: background class such as 'vd_bg-white' padding or any other class
        $data['body_extra_class'] = ''; // 'content-style-2' : No Border Bottom on Title, 'login-layout' Make Specific layout for login, register, and forget password page,
        // 'front-layout' : Make Specific layout for login, register, and forget password page, 'remove-navbar' : Using no sidebar at all,

        /*  EXTRA CLASS SETTING  */

        $data['keywords'] = 'This is keyword';
        $data['description'] = 'This is description';

        // Minimal
        $data['navbar_left_extra_class'] = 'vd_navbar-style-2';

        return $data;
    }

    public function getDashboardTemplateData()
    {
        $data = $this->getTemplateData();
        $data['title'] = 'Multipurpose Dashboard - Responsive Multipurpose Admin Templates';
        $data['page'] = 'dashboard';   // To set active on the same id of primary menu

        // Additional Specific CSS
        $data['specific_css'][0] = 'plugins/fullcalendar/fullcalendar.css';
        $data['specific_css'][1] = 'plugins/fullcalendar/fullcalendar.print.css';
        $data['specific_css'][2] = 'plugins/introjs/css/introjs.min.css';
        return $data;
    }

    public function getViewerTemplateData()
    {
        $data = $this->getTemplateData();
        $data['title'] = 'IGM Viewer';
        $data['page'] = 'viewer';   // To set active on the same id of primary menu

        return $data;
    }

    public function getEventTemplateData()
    {
        $data = $this->getTemplateData();
        $data['title'] = 'Events';
        $data['page'] = 'event';   // To set active on the same id of primary menu

        return $data;
    }

    public function getBrandTemplateData()
    {
        $data = $this->getTemplateData();
        $data['title'] = 'IGM Brand';
        $data['page'] = 'viewer';   // To set active on the same id of primary menu

        return $data;
    }

    public function getRegisterTemplateData()
    {
        $data = $this->getTemplateData();
        $data['title'] = 'IGM Register';
        $data['footer'] = 'footer-blank';
        $data['header'] = 'header-blank';

        $data['body_extra_class'] = 'remove-navbar login-layout';
        $data['background'] = 'background-login';
        $data['navbar_left_config'] = 0;
        $data['navbar_right_config'] = 0;
        return $data;
    }

    public function getLoginTemplateData()
    {
        $data = $this->getRegisterTemplateData();
        $data['title'] = 'IGM Login';

        return $data;
    }

    public function getWorkerAccessToken()
    {
        $brand = UserToken::where('id','>',0)->orderBy(DB::raw('RAND()'))->first();
        return $brand->token;
    }

    public function getDummyMedia() {
        return json_decode('{"attribution":null,"tags":["popmeth","cute","igers","thaistagram","igth","all_shots","cat"],"type":"image","location":null,"comments":{"count":4,"data":[{"created_time":"1329825141","text":"lovely","from":{"username":"c1nda","profile_picture":"https:\/\/igcdn-photos-c-a.akamaihd.net\/hphotos-ak-xfp1\/t51.2885-19\/10424575_865605413454130_110165409_a.jpg","id":"13189267","full_name":"Cinder"},"id":"130905231647785057"},{"created_time":"1329832530","text":"\u0e2a\u0e27\u0e22\u0e08\u0e31\u0e07\u0e40\u0e25\u0e22\u0e22\u0e22\u0e22\u0e22\u0e22\u0e22^___^*","from":{"username":"thomtham","profile_picture":"https:\/\/instagramimages-a.akamaihd.net\/profiles\/profile_283149_75sq_1384094672.jpg","id":"283149","full_name":"thomtham"},"id":"130967217169449199"},{"created_time":"1329839654","text":"@thomtham \u0e14\u0e35\u0e08\u0e31\u0e22\u0e22 ^^~","from":{"username":"tsupol","profile_picture":"https:\/\/instagramimages-a.akamaihd.net\/profiles\/profile_15771743_75sq_1386699456.jpg","id":"15771743","full_name":"Ton Supol"},"id":"131026977428292050"},{"created_time":"1329839759","text":"#igers #igth #thaistagram #cat #cute #popmeth #all_shots","from":{"username":"tsupol","profile_picture":"https:\/\/instagramimages-a.akamaihd.net\/profiles\/profile_15771743_75sq_1386699456.jpg","id":"15771743","full_name":"Ton Supol"},"id":"131027853291241940"}]},"filter":"Normal","created_time":"1329823757","link":"https:\/\/instagram.com\/p\/HRBw9TG0A-\/","likes":{"count":46,"data":[{"username":"felixapollo11","profile_picture":"https:\/\/igcdn-photos-g-a.akamaihd.net\/hphotos-ak-xfa1\/t51.2885-19\/10881944_324886491039150_414305296_a.jpg","id":"16083297","full_name":"Felix Apollo11"},{"username":"couplelotus","profile_picture":"https:\/\/igcdn-photos-b-a.akamaihd.net\/hphotos-ak-xfa1\/t51.2885-19\/10963880_903494433036849_693675900_a.jpg","id":"16148026","full_name":"Couple Lotus"},{"username":"oubibb","profile_picture":"https:\/\/igcdn-photos-e-a.akamaihd.net\/hphotos-ak-xaf1\/t51.2885-19\/11084866_1541611469437604_1260279011_a.jpg","id":"17781342","full_name":"oubibb"},{"username":"anastasia_skazkina","profile_picture":"https:\/\/igcdn-photos-g-a.akamaihd.net\/hphotos-ak-xfa1\/t51.2885-19\/11056001_807268759364542_780692538_a.jpg","id":"16947431","full_name":"ANASTASIA SKAZKINA"}]},"images":{"low_resolution":{"url":"https:\/\/scontent.cdninstagram.com\/hphotos-xfa1\/outbound-distilleryimage6\/t0.0-17\/OBPTH\/4a900f485c7f11e180c9123138016265_6.jpg","width":306,"height":306},"thumbnail":{"url":"https:\/\/scontent.cdninstagram.com\/hphotos-xfa1\/outbound-distilleryimage6\/t0.0-17\/OBPTH\/4a900f485c7f11e180c9123138016265_5.jpg","width":150,"height":150},"standard_resolution":{"url":"https:\/\/scontent.cdninstagram.com\/hphotos-xfa1\/outbound-distilleryimage6\/t0.0-17\/OBPTH\/4a900f485c7f11e180c9123138016265_7.jpg","width":612,"height":612}},"users_in_photo":[],"caption":null,"user_has_liked":true,"id":"130893626570653758_15771743","user":{"username":"tsupol","profile_picture":"https:\/\/instagramimages-a.akamaihd.net\/profiles\/profile_15771743_75sq_1386699456.jpg","id":"15771743","full_name":"Ton Supol"}}');
    }

}