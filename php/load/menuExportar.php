<?php
	header("Content-Type: text/plain"); 
	
	$numFase = isset($_POST['numFase'])?$_POST['numFase']:'';
	
	switch(strtoupper($numFase))
	{
		case '3':
			$menu = "[";
			$menu.= "{xtype : 'menutextitem',text :'Parametros', style: {'border':'1px solid #999999', 'margin':'0px 0px 1px 0px', 'display':'block', 'padding':'3px', 'text-align':'center', 'font-size':'12px',	'font-weight':'bold', 'background-color':'#D6E3F2'}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'V1', checkHandler : function(menuItem, choice){eV1=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'V2', checkHandler : function(menuItem, choice){eV2=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'V3', checkHandler : function(menuItem, choice){eV3=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'I1', checkHandler : function(menuItem, choice){eI1=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'I2', checkHandler : function(menuItem, choice){eI2=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'I3', checkHandler : function(menuItem, choice){eI3=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'S1', checkHandler : function(menuItem, choice){eS1=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'S2', checkHandler : function(menuItem, choice){eS2=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'S3', checkHandler : function(menuItem, choice){eS3=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'ST', checkHandler : function(menuItem, choice){eST=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'PF1',checkHandler : function(menuItem, choice){ePF1=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'PF2',checkHandler : function(menuItem, choice){ePF2=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'PF3',checkHandler : function(menuItem, choice){ePF3=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'PFT',checkHandler : function(menuItem, choice){ePFT=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'WT', checkHandler : function(menuItem, choice){eWT=choice?1:0;}}";
			$menu.= "]";
			break;
		case '2': 
			$menu = "[";
			$menu.= "{xtype : 'menutextitem',text :'Parametros', style: {'border':'1px solid #999999', 'margin':'0px 0px 1px 0px', 'display':'block', 'padding':'3px', 'text-align':'center', 'font-size':'12px',	'font-weight':'bold', 'background-color':'#D6E3F2'}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'V1', checkHandler : function(menuItem, choice){eV1=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'V2', checkHandler : function(menuItem, choice){eV2=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'I1', checkHandler : function(menuItem, choice){eI1=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'I2', checkHandler : function(menuItem, choice){eI2=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'S1', checkHandler : function(menuItem, choice){eS1=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'S2', checkHandler : function(menuItem, choice){eS2=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'ST', checkHandler : function(menuItem, choice){eST=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'PF1',checkHandler : function(menuItem, choice){ePF1=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'PF2',checkHandler : function(menuItem, choice){ePF2=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'PFT',checkHandler : function(menuItem, choice){ePFT=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'WT', checkHandler : function(menuItem, choice){eWT=choice?1:0;}}";
			$menu.= "]";
			break;
		default:
			$menu = "[";
			$menu.= "{xtype : 'menutextitem',text :'Error de Carga', style:{'border':'1px solid #999999', 'background-color':'#D6E3F2', 'margin':\"0px 0px 1px 0px\", ";
			$menu.= "'display':'block', 'padding':'3px', 'font-weight':'bold', 'font-size':'12px', 'text-align':'center'}} ";
			$menu.= "]";
	}	
	echo $menu;

?>