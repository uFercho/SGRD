<?php
	header("Content-Type: text/plain"); 
	
	$numFase = isset($_POST['numFase'])?$_POST['numFase']:'';
	
	switch(strtoupper($numFase))
	{
		case '3':
			$menu = "[";
			$menu.= "{xtype : 'menutextitem', text :'Filtro', style: {'border':'1px solid #999999', 'margin':'0px 0px 1px 0px', 'display':'block', 'padding':'3px', 'text-align':'center', 'font-size':'12px', 'font-weight':'bold', 'background-color':'#D6E3F2'}},";
			$menu.= "{text : 'Fase 1', iconcls : 'not_yet_defined', checked : true, group : 'fases' , handler : function(menuItem, choice){cFase='f1'}},";
			$menu.= "{text : 'Fase 2', iconcls : 'not_yet_defined', checked : false, group : 'fases', handler : function(menuItem, choice){cFase='f2'}},";
			$menu.= "{text : 'Fase 3', iconcls : 'not_yet_defined', checked : false, group : 'fases', handler : function(menuItem, choice){cFase='f3'}},";
			$menu.= "'-',";
			$menu.= "{xtype : 'menutextitem',text :'Parametros', style: {'border':'1px solid #999999', 'margin':'0px 0px 1px 0px', 'display':'block', 'padding':'3px', 'text-align':'center', 'font-size':'12px', 'font-weight':'bold', 'background-color':'#D6E3F2'}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : '[V]  Voltaje'  , checkHandler : function(menuItem, choice){cV=choice?1:0;}, listeners: {render: function(){cV=1;}}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : '[I]  Corriente', checkHandler : function(menuItem, choice){cI=choice?1:0;}, listeners: {render: function(){cI=1;}}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : '[S]  Potencia' , checkHandler : function(menuItem, choice){cS=choice?1:0;}, listeners: {render: function(){cS=1;}}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : '[PF] FPotencia', checkHandler : function(menuItem, choice){cPf=choice?1:0;}, listeners: {render: function(){cPf=1;}}}";
			$menu.= "]";
			break;
		case '2': 
			$menu = "[";
			$menu.= "{xtype : 'menutextitem', text :'Filtro', style: {'border':'1px solid #999999', 'margin':'0px 0px 1px 0px', 'display':'block', 'padding':'3px', 'text-align':'center', 'font-size':'12px', 'font-weight':'bold', 'background-color':'#D6E3F2'}},";
			$menu.= "{text : 'Fase 1', iconcls : 'not_yet_defined', checked : true, group : 'fases' , handler : function(menuItem, choice){cFase='f1'}},";
			$menu.= "{text : 'Fase 2', iconcls : 'not_yet_defined', checked : false, group : 'fases', handler : function(menuItem, choice){cFase='f2'}},";
			$menu.= "'-',";
			$menu.= "{xtype : 'menutextitem',text :'Parametros', style: {'border':'1px solid #999999', 'margin':'0px 0px 1px 0px', 'display':'block', 'padding':'3px', 'text-align':'center', 'font-size':'12px', 'font-weight':'bold', 'background-color':'#D6E3F2'}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : '[V]  Voltaje'  , checkHandler : function(menuItem, choice){cV=choice?1:0;}, listeners: {render: function(){cV=1;}}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : '[I]  Corriente', checkHandler : function(menuItem, choice){cI=choice?1:0;}, listeners: {render: function(){cI=1;}}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : '[S]  Potencia' , checkHandler : function(menuItem, choice){cS=choice?1:0;}, listeners: {render: function(){cS=1;}}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : '[PF] FPotencia', checkHandler : function(menuItem, choice){cPf=choice?1:0;}, listeners: {render: function(){cPf=1;}}}";
			$menu.= "]";
			break;
		case '1': 
			$menu = "[";
			$menu.= "{xtype : 'menutextitem', text :'Filtro', style: {'border':'1px solid #999999', 'margin':'0px 0px 1px 0px', 'display':'block', 'padding':'3px', 'text-align':'center', 'font-size':'12px', 'font-weight':'bold', 'background-color':'#D6E3F2'}},";
			$menu.= "{text : 'Fase 1', iconcls : 'not_yet_defined', checked : true, group : 'fases' , handler : function(menuItem, choice){cFase='f1'}},";
			$menu.= "'-',";
			$menu.= "{xtype : 'menutextitem',text :'Parametros', style: {'border':'1px solid #999999', 'margin':'0px 0px 1px 0px', 'display':'block', 'padding':'3px', 'text-align':'center', 'font-size':'12px', 'font-weight':'bold', 'background-color':'#D6E3F2'}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : '[V]  Voltaje'  , checkHandler : function(menuItem, choice){cV=choice?1:0;}, listeners: {render: function(){cV=1;}}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : '[I]  Corriente', checkHandler : function(menuItem, choice){cI=choice?1:0;}, listeners: {render: function(){cI=1;}}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : '[S]  Potencia' , checkHandler : function(menuItem, choice){cS=choice?1:0;}, listeners: {render: function(){cS=1;}}},";
			$menu.= "{xtype: 'menucheckitem', hideOnClick: false, checked: true, text : '[PF] FPotencia', checkHandler : function(menuItem, choice){cPf=choice?1:0;}, listeners: {render: function(){cPf=1;}}}";
			$menu.= "]";
			break;
		default:
			$menu = "[";
			$menu.= "{xtype : 'menutextitem', text :'Error de Carga', style:{'border':'1px solid #999999', 'background-color':'#D6E3F2', 'margin':\"0px 0px 1px 0px\", ";
			$menu.= "'display':'block', 'padding':'3px', 'font-weight':'bold', 'font-size':'12px', 'text-align':'center'}} ";
			$menu.= "]";
	}	
	echo $menu;

?>