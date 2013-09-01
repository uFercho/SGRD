<?php
	header("Content-Type: text/plain"); 
	
	$numFase = isset($_POST['numFase'])?$_POST['numFase']:'';
	
	switch(strtoupper($numFase))
	{
		case '3':
			$menu = "[";
			$menu.= "{xtype : 'menutextitem', text :'Seleccione uno', style: {'border':'1px solid #999999', 'margin':'0px 0px 1px 0px', 'display':'block', 'padding':'3px', 'text-align':'center', 'font-size':'12px', 'font-weight':'bold', 'background-color':'#D6E3F2'}},";
			$menu.= "{text : '[V]  Voltaje'  , iconcls : 'not_yet_defined', checked : true,  group : 'tipo', handler : function(menuItem, choice){cTipo='V'}},";
			$menu.= "{text : '[I]  Corriente', iconcls : 'not_yet_defined', checked : false, group : 'tipo', handler : function(menuItem, choice){cTipo='I'}},";
			$menu.= "{text : '[S]  Potencia' , iconcls : 'not_yet_defined', checked : false, group : 'tipo', handler : function(menuItem, choice){cTipo='S'}},";
			$menu.= "{text : '[PF] FPotencia', iconcls : 'not_yet_defined', checked : false, group : 'tipo', handler : function(menuItem, choice){cTipo='PF'}},";
			$menu.= "'-',";
			$menu.= "{xtype : 'menutextitem',text :'Parametros', style: {'border':'1px solid #999999', 'margin':'0px 0px 1px 0px', 'display':'block', 'padding':'3px', 'text-align':'center', 'font-size':'12px',	'font-weight':'bold', 'background-color':'#D6E3F2'}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'Fase 1', checkHandler : function(menuItem, choice){f1=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'Fase 2', checkHandler : function(menuItem, choice){f2=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'Fase 3', checkHandler : function(menuItem, choice){f3=choice?1:0;}}";
			$menu.= "]";
			break;
		case '2': 
			$menu = "[";
			$menu.= "{xtype : 'menutextitem', text :'Seleccione uno', style: {'border':'1px solid #999999', 'margin':'0px 0px 1px 0px', 'display':'block', 'padding':'3px', 'text-align':'center', 'font-size':'12px', 'font-weight':'bold', 'background-color':'#D6E3F2'}},";
			$menu.= "{text : '[V]  Voltaje'  , iconcls : 'not_yet_defined', checked : true,  group : 'tipo', handler : function(menuItem, choice){cTipo='V'}},";
			$menu.= "{text : '[I]  Corriente', iconcls : 'not_yet_defined', checked : false, group : 'tipo', handler : function(menuItem, choice){cTipo='I'}},";
			$menu.= "{text : '[S]  Potencia' , iconcls : 'not_yet_defined', checked : false, group : 'tipo', handler : function(menuItem, choice){cTipo='S'}},";
			$menu.= "{text : '[PF] FPotencia', iconcls : 'not_yet_defined', checked : false, group : 'tipo', handler : function(menuItem, choice){cTipo='PF'}},";
			$menu.= "'-',";
			$menu.= "{xtype : 'menutextitem',text :'Parametros', style: {'border':'1px solid #999999', 'margin':'0px 0px 1px 0px', 'display':'block', 'padding':'3px', 'text-align':'center', 'font-size':'12px',	'font-weight':'bold', 'background-color':'#D6E3F2'}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'Fase 1', checkHandler : function(menuItem, choice){f1=choice?1:0;}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'Fase 2', checkHandler : function(menuItem, choice){f2=choice?1:0;}}";
			$menu.= "]";
			break;
		case '1': 
			$menu = "[";
			$menu.= "{xtype : 'menutextitem', text :'Filtro', style: {'border':'1px solid #999999', 'margin':'0px 0px 1px 0px', 'display':'block', 'padding':'3px', 'text-align':'center', 'font-size':'12px', 'font-weight':'bold', 'background-color':'#D6E3F2'}},";
			$menu.= "{text : '[V]  Voltaje'  , iconcls : 'not_yet_defined', checked : true,  group : 'tipo', handler : function(menuItem, choice){cTipo='V'}},";
			$menu.= "{text : '[I]  Corriente', iconcls : 'not_yet_defined', checked : false, group : 'tipo', handler : function(menuItem, choice){cTipo='I'}},";
			$menu.= "{text : '[S]  Potencia' , iconcls : 'not_yet_defined', checked : false, group : 'tipo', handler : function(menuItem, choice){cTipo='S'}},";
			$menu.= "{text : '[PF] FPotencia', iconcls : 'not_yet_defined', checked : false, group : 'tipo', handler : function(menuItem, choice){cTipo='PF'}},";
			$menu.= "'-',";
			$menu.= "{xtype : 'menutextitem',text :'Parametros', style: {'border':'1px solid #999999', 'margin':'0px 0px 1px 0px', 'display':'block', 'padding':'3px', 'text-align':'center', 'font-size':'12px',	'font-weight':'bold', 'background-color':'#D6E3F2'}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : 'Fase 1', checkHandler : function(menuItem, choice){f1=choice?1:0;}}";
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