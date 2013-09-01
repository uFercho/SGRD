<html> 
<head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
    <title>SGRD</title>  
    <!--[if IE]><script language="javascript" type="text/javascript" src="jqplot/excanvas.js"></script><![endif]-->
    
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
 
    <!-- ** CSS ** layout-browser --> 
    <!-- Extjs base library --> 
    <link rel="stylesheet" type="text/css" href="ext/resources/css/ext-all.css" />
    <link rel="stylesheet" type="text/css" href="ext/resources/css/xtheme-slate.css" />
    
    <!-- jqplot base library -->  
    <link rel="stylesheet" type="text/css" href="jqplot/css/jquery.jqplot.css" />
 
    <!-- Extjs overrides to base library --> <!--
    <link rel="stylesheet" type="text/css" href="ext/examples/ux/css/CenterLayout.css" /> -->
 
    <!-- Extjs page specific --> 
    <link rel="stylesheet" type="text/css" href="css/PQ-Analytics.css">
    <link rel="stylesheet" type="text/css" href="ext/examples/shared/examples.css" />
    <link rel="stylesheet" type="text/css" href="ext/examples/ux/fileuploadfield/css/fileuploadfield.css"/>

  
    <!-- ** Javascript ** --> 
    <!-- ExtJS library: base/adapter --> 
    <script type="text/javascript" src="ext/adapter/ext/ext-base.js"></script> 
 
    <!-- ExtJS library: all widgets --> 
    <script type="text/javascript" src="ext/ext-all.js"></script>
    
    <!-- ExtJS library: lang espanish -->
	<script type="text/javascript" src="ext/locale/ext-lang-es.js"></script>  
    
    <!-- jquery library: all widgets --> 
	<script language="javascript" type="text/javascript" src="jqplot/jquery-1.4.2.min.js"></script>
    
    <!-- jqplot library: all widgets -->
    <script language="javascript" type="text/javascript" src="jqplot/jquery.jqplot.js"></script>
 
    <!-- ExtJS library: extensions -->
    <script type="text/javascript" src="ext/examples/ux/fileuploadfield/FileUploadField.js"></script>
    <script type="text/javascript" src="ext/examples/ux/storemenu/ext.ux.menu.storemenu.js"></script>
    <!--<script type="text/javascript" src="ext/examples/ux/monthpicker/MonthPickerPlugin.js"></script>-->
    
    <!-- jqplot library: extensions --> 
    <script language="javascript" type="text/javascript" src="jqplot/plugins/jqplot.dateAxisRenderer.js"></script>
    <script language="javascript" type="text/javascript" src="jqplot/plugins/jqplot.highlighter.js"></script>
    <script language="javascript" type="text/javascript" src="jqplot/plugins/jqplot.cursor.js"></script>
    <script language="javascript" type="text/javascript" src="jqplot/plugins/jqplot.barRenderer.js"></script>
	<script language="javascript" type="text/javascript" src="jqplot/plugins/jqplot.pointLabels.js"></script>
    <script language="javascript" type="text/javascript" src="jqplot/plugins/jqplot.categoryAxisRenderer.js"></script>
    
    <!-- jshash library: extensions --> 
    <!--<script type="text/javascript" src="js/jshash/md5-min.js"></script>-->
 
    <!-- page specific -->  
    <script type="text/javascript" src="js/util.js"></script>
    <script type="text/javascript" src="js/basic.js"></script>
    <script type="text/javascript" src="js/PQ-Analytics.js"></script>
    <script type="text/javascript" src="js/limit-volt-cp.js"></script> 
    

 
</head> 
<body> 
    <div id="north" class="x-panel-header" style="margin:0px 5px 0px 5px; padding: 2px 2px 5px 10px; ">
        <div style="float:left; width: 500px; font-size:18px; line-height:22px; " id="ext-gen267">
            SGRD - Sistema de Gesti&oacute;n de la Red de Distribuci&oacute;n
        </div> 
        <div id="external-menu" align="left"></div>
    </div>
    <div style="display:none;"> 
        <!-- Start page content --> 
        <div id="start-div">
        	<div id="fondoestirado">
            	<img src="images/background.jpg" alt="" />
        	</div>  
            <!--<div style="float:left;" ><img src="images/layout-icon.gif" /></div> 
            <div style="margin-left:100px;"> 
                <h2>Bienvenido!</h2> 
                <p>There are many sample layouts to choose from that should give you a good head start in building your own
                application layout.  Just like the combination examples, you can mix and match most layouts as
                needed, so don't be afraid to experiment!</p> 
                <p>Select a layout from the tree to the left to begin.</p> 
            </div> -->
        </div> 
        <div id="logo-div"> 
            <div id="center1" align="center" style="padding:5px; ">
                <div align="center" style="background: url('images/logo.png') no-repeat; width: 295px; height: 90px;"></div>
            </div>
        </div>
 
        <!-- Basic layouts --> 
        <div id="absolute-details"> 
            <h2>Ext.layout.AbsoluteLayout</h2> 
            <p>This is a simple layout style that allows you to position items within a container using
            CSS-style absolute positioning via XY coordinates.</p> 
            <p><b>Sample Config:</b></p> 
            <pre><code> 
layout: 'absolute',
items:[{
    title: 'Panel 1',
    x: 50,
    y: 50,
    html: 'Positioned at x:50, y:50'
}]
            </code></pre> 
            <p><a href="http://extjs.com/deploy/dev/docs/?class=Ext.layout.AbsoluteLayout" target="_blank">API Reference</a></p> 
        </div> 
        
    </div>
</body> 
</html> 