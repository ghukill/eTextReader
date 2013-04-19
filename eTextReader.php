<?php
//checks authentication status
include "php/ebook_auth.php";

if ($auth_status == "deny"){    
    header( "Location: http://proxy.lib.wayne.edu/login?url=".curPageURL()."#page/1/mode/2up");    
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
    <title id="doc_title">WSU eTextReader</title>   
   
    <!--jquery load, 1.4 works best -->
    <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>       
    <script type="text/javascript" src="js/jquery-ui-1.8.5.custom.min.js"></script>
    <script type="text/javascript" src="js/dragscrollable.js"></script>
    <!--<script type="text/javascript" src="js/jquery.ui.ipad.js"></script>-->
    <script type="text/javascript" src="js/jquery.bt.min.js"></script>

    <!-- Main BookReader CSS -->    
    <link id="css_icons" rel="stylesheet" type="text/css" href="css/elusive-iconset/css/elusive-webfont.css"/>
    <!--[if lte IE 7]><script src="css/elusive-iconset/lte-ie7.js"></script><![endif]-->    
    <link id="css_pointer" rel="stylesheet" type="text/css" href="css/orig_stylesheet.css"/>
    <link id="css_pointer" rel="stylesheet" type="text/css" href="css/WSU_def_stylesheet.css"/>
    <link id="css_pointer" rel="stylesheet" type="text/css" href="css/WSU_layout.css"/>

    <!-- Load BR init and local functionality -->    
    <script type="text/javascript" src="js/WSU_bookreader.js"></script>        
    <script type="text/javascript" src="js/WSU_js.js"></script>
    <script type="text/javascript" src="js/WSU_launch.js"></script>        
    <script type="text/javascript" src="js/fullscreen_API.js"></script>

    <!--URL hash detector / used to detect page changing-->
    <script type="text/javascript" src="js/hashchange.js"></script>

    <!--jplayer -->
    <script type="text/javascript" src="inc/jPlayer/jquery.jplayer.min.js"></script>    

    <!--jquery highlight-->
    <script type="text/javascript" src="js/jquery.highlight.js"></script> 

    <!--colorbox for metadata information-->
    <script src="js/jquery.colorbox-min.js"></script>
    <link rel="stylesheet" type="text/css" href="inc/colorbox/itemMetadata/colorbox.css"/>

    <!--mobile detection script-->
    <script src="js/detectMobile.js"></script>

    <!--NON-MOBILE ONLY-->
    <!--magnifier / tjpzoom -->    
    <script type="text/javascript" src="js/jquery.loupe.js"></script>
    
    <!-- Modernizr -->
    <script type="text/javascript" src="js/modernizr.js"></script>

    <!--Opentip-->
    <script src="inc/opentip/opentip-native-excanvas.min.js"></script>
    <link href="inc/opentip/opentip.css" rel="stylesheet" type="text/css" />

</head>

<body>
    <div id="bookreader_wrapper">    

        <div id="overlays"></div>

        <div id="WSUtoolbar" class="shadow">

            <div class="tool_row">

                <div id="first_row">                    
                    <div class="logo tools left">
                        <a href="http://www.lib.wayne.edu/" style="text-decoration:none;"><img id="lib_logo" src="./images/icons/library_system_w_v2.png" /></a>
                    </div>

                    <div id="navTip" class="icon tools left">                                                
                        <ul class="the-icons">                        
                            <li><i class="icon-step-backward tooltip" onclick='br.leftmost(); return false;' data-ot="Beginning of Text"></i></li>
                            <li><i id="previousArrow" class="icon-arrow-left" onclick='br.left(); return false;' data-ot="Previous Page"></i></li>
                            <li><i id="nextArrow" class="icon-arrow-right" onclick='br.right(); return false;' data-ot="Next Page"></i></li>                                                                        
                            <li><i class="icon-step-forward" onclick='br.rightmost(); return false;' data-ot="End of Text"></i></li>                                            
                        </ul>            
                    </div>                   

                    <div class="icon tools left" id="jump_to_form">
                        <div id="jump_form_div" data-ot="Jump to Page">
                            <form action='javascript:' onsubmit='br.jumpToPage(this.elements[0].value)'>
                                <span id="jump_to_span" class='label'>Page<input id='BRpagenum' type='text' size='3' onfocus='br.autoStop();'/>of <span id="leaf_count"></span></span>
                            </form>
                        </div>
                    </div>

                    <div class="icon tools left" id="cogIcon">
                        <ul class="the-icons">                        
                            <li><i class="icon-cog" onclick='toggleMoreTools(); return false;'></i></li>
                        </ul>
                    </div>                                                          
                    
                    <div id="first_row_pivot" class="collapseRow">                        
                        <div class="fts_form left first" id="fts_search" data-ot="Search this Text">
                            <label>Full-text search:</label>                
                            <form id="fts_form" action='javascript:' onsubmit='getFTSResultsStatic(0)'>
                                <input id='fts_input' class="fts_input_box" type='text' size='20' />                                
                                <!-- <button id="fts_form_button" type="button" class="btn btn-append" onclick="getFTSResultsStatic(0); return false;">search</button>  -->
                                <button id="fts_form_button" type="button" onclick="getFTSResultsStatic(0); return false;">search</button>                                 
                            </form>                
                        </div>
                    </div>                    
                </div> <!--closes first row-->

                <div id="second_row" class="collapseRow">
                    <div class="icon tools left" id="second_row_leftmost">
                        <ul class="the-icons">       
                            <li><i class="icon-zoom-out" onclick='br.zoom(-1); return false;' title="Zoom out" data-ot="Zoom Out"></i></li>
                            <li><i class="icon-zoom-in" onclick='br.zoom(1); return false;' title="Zoom in" data-ot="Zoom In"></i></li>
                            <li><span id="zoom_level" class='label'><span id='BRzoom'></span></span></li>
                        </ul>                        
                    </div>                 
                    
                    <div rel="tooltip" class="icon tools right second">                                                 
                        <ul id="mode_icons" class="the-icons">                        
                            <li><i id="1up_icon" class="icon-file" onclick="launch1up(); return false;" data-ot="One Page Mode"></i></li>
                            <li><i id="2up_icon" class="icon-book" onclick="launch2up(); return false;" data-ot="Two Page Mode"></i></li>
                            <li><i id="thumbs_icon" class="icon-th" onclick="launchThumbs(); return false;" data-ot="Thumbnail Mode"></i></li>
                            <li><i id="plain_text_icon" class="icon-align-left" onclick='plainText();' data-ot="Plain Text / HTML Mode"></i></li>
                        </ul>                    
                    </div>             

                    <div id="second_row_pivot" >
                        <div class="icon tools right second">
                            <ul class="the-icons">                                
                                <li><i class="toggleOCR icon-file-alt" onclick='toggleOCR(); return false;' data-ot="OCR Text Overlay"></i></li>                
                                <li><i style="display:none;" class="OCR_tools icon-plus" onclick='fontResize("increase"); return false;' data-ot="Increase Text Size"></i></li>
                                <li><i style="display:none;" class="OCR_tools icon-minus" onclick='fontResize("decrease"); return false;' data-ot="Decrease Text Size"></i></li>
                                <li><i class="icon-speaker toolbarTip" onclick='speakPagealoud();' data-ot="Speak Text Aloud"></i></li>                         
                                <li><i class="icon-screenshot" onclick='magLoupe();' data-ot="Maginfying Loupe"></i></li>
                                <li><i class="icon-resize-full" onclick='goFullScreen();' data-ot="Full-Screen"></i></li>
                                <li><i class="icon-info-sign" onclick='itemInfo();' data-ot="Information about this Text"></i></li>
                                <li><i class="icon-question-sign" onclick='help_eTextReader();' data-ot="Help about the eTextReader"></i></li>                                        
                            </ul>                                                     
                        </div>
                    </div>
                </div> <!--closes second row-->                                    

            </div> <!--closes tool row div -->             

        </div>

        <!--Set minimize arrow outside of toolbar structure, turned on in postLaunch function -->
        <div id="WSUtoolbar_minimize" class="toolbar_exposed" onclick="toolbarsMinimize(); return false;" data-ot="Show/Hide Toolbar">
            <ul class="the-icons">                        
                <li><i id="minimize_handle" class="icon-chevron-up"></i></li>
            </ul>
        </div>            

        <!-- Containing and target DIV for bookreader construction-->
        <div id="BookReader">        
            <p>This book reader requires JavaScript to be enabled. Please check that your browser or device supports JavaScript and that it is enabled.</p>           
        </div>

        <!--full-text search infrastructure-->
        <div id="fts_box_text_static" class="shadow"></div>        

    </div> <!--closes bookreader_wrapper-->
</body>

<script type="text/javascript">
    // preLaunch();
    preLaunch(<?php echo "'$img_rewrite'"; ?>);
</script>

</html>
