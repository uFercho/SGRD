/*!
 * Ext JS Library 3.3.0
 * Copyright(c) 2006-2010 Ext JS, Inc.
 * licensing@extjs.com
 * http://www.extjs.com/licenseF
 *
 * Desarrollador: Nelson Suarez 
 * nelson.suarezp@gmail.com
 */

var root = '';   //id del registro seleccionado
var filter = ''; //filtro de la consulta para el grid de CP
var numFase; //
var numEquip; //
var idUT; //
var reg_s = 'reg_st'; //
var statsUE = 'SC'; //

var cTipo = 'V'; //parametro que se va a graficar en funcion de las fases. por defecto es el voltaje
var f1 = 1; //indica que la fase uno  va a ser tomada en cuenta para la grafica de cTipo s1 o n0
var f2 = 1; //indica que la fase dos  va a ser tomada en cuenta para la grafica de cTipo s1 o n0
var f3 = 1; //indica que la fase tres va a ser tomada en cuenta para la grafica de cTipo s1 o n0

var cFase = 'f1'; //fase que se va a graficar en funcion de los parametros. por defecto es la fase 1
var cV = 1; //indica que el voltaje va a ser tomado en cuenta para la grafica de cFase s1 o n0
var cI = 1; //indica que la corriente va a ser tomada en cuenta para la grafica de cFase s1 o n0
var cS = 1; //indica que la potencia va a ser tomada en cuenta para la grafica de cFase s1 o n0
var cPf= 1; //indica que el factor de potencia va a ser tomado en cuenta para la grafica de cFase s1 o n0

var Otros = 'Vp'; //indica que parametro de la base de datos se va a mostrar en la grafica.

var eV1 = 1;
var eV2 = 1;
var eV3 = 1;
var eI1 = 1;
var eI2 = 1;
var eI3 = 1;
var eS1 = 1;
var eS2 = 1;
var eS3 = 1;
var eST = 1;
var ePF1= 1;
var ePF2= 1;
var ePF3= 1;
var ePFT= 1;
var eWT = 1;

var h1 = '07'; // inicio del  1er turno en la grafica de sobrecarga
var h2 = '15'; // inicio del  2do turno en la grafica de sobrecarga
var h3 = '20'; // inicio del  3er turno en la grafica de sobrecarga

var colGrid = [];
var colResum = [];
var colGridCP = [];
var colTablaCP = [];
var colUsuKva = [];
var colKvaFase = [];
var colCorriMT = [];
var colStatusMT = [];
var colFactorMT = [];
var colMtoEquipo = [];

var longCol = new Number;

var por_v = 10;	//varicion en porcentaje del voltaje nominal
var voltn = 120;//voltaje nominal

/* ================================================================
 * ======================  adminUsers  ============================
 * ================================================================
 */

function Validar(){
	if (loginForm.form.isValid()) {
		//Ext.getCmp('field-pass').setValue();
		loginForm.form.submit({
			waitTitle : "Validando",			
			url       : 'php/login/getUsuario.php',
			params: {
				password: Ext.util.MD5(Ext.getCmp('field-pass').getValue())
			},
			waitMsg   : "Espere un momento por favor......",
			failure   : function(sender,action){
				Ext.utiles.msg('Error!', action.result.msg);/*
				try{
					if(action.result.msg!=null)
						//Ext.utiles.msg('Error de Validaci&oacute;n', action.result.msg);
					else
						throw Exception();
				}
				catch(Exception){
					//msgAlt('Error durante el proceso','Vuelva a Intentarlo<br>Perdone las molestias.').show();
				}
			*/},
			success: function(sender,action) {
				//Ext.example.msg('Click','You clicked on "Action 1".');
				//actualizarEmpresaConectada();
				Ext.utiles.msg('Correcto!', action.result.msg);
				Ext.getCmp('add-cvs').enable();
				Ext.getCmp('add-txt').enable();
				loginForm.getForm().reset();
				Ext.getCmp('win-login').hide();
			}
		});
	}
};

var loginForm = new Ext.form.FormPanel({
	baseCls: 'x-plain',
	labelWidth: 180,
	autoWidth:true,
	autoHeight:true,
	frame:true,
	autoScroll:false,
	bodyStyle:'padding:10px;',
	url:'localhost',
	items: [{
		xtype:'fieldset',
		title:'Usuario / Password', 
		autoWidth:true, 
		labelWidth: 90, 
		autoHeight:true, 
		defaultType: 'textfield',
		items:[
			{fieldLabel:'Usuario', name: 'login', allowBlank:false, maxLength:250, anchor:'80%'},
			{fieldLabel:'Password', inputType:'password', allowBlank:false, maxLength:20, name: 'password', anchor:'80%', id: 'field-pass', submitValue: false}
		]
	}]
}); 

/* ================================================================
 * ==================  Mto Utran y Equipos  =======================
 * ================================================================
 */
 
var storeMtoEquipo = new Ext.data.JsonStore({
	url : 'php/load/gridMtoEquipo.php'
});

var pageMtoEquipo = new Ext.PagingToolbar({
	store       : storeMtoEquipo, // <--grid and PagingToolbar using same store (required)
	displayInfo : true,
	displayMsg  : '{0} - {1} de {2} Equipos',
	emptyMsg    : 'No hay Muestras para mostrar',
	pageSize    : 25
});


pageMtoEquipo.on('beforechange',function(bar,params){  
	
});

var gridMtoEquipo = {
	id          	 : 'MtoEquipo',
	xtype       	 : 'editorgrid',
	autoDestroy 	 : true,
	layout           : 'fit',
	iconCls			 : 'icon-grid',
	collapsible 	 : false,
	enableColumnMove : false,
	border      	 : false,
	stateful    	 : true,
	clicksToEdit	 : 2,
	columnLines		 : true,
	loadMask    	 : true,//{msg:"Cargando..."},						
	stripeRows  	 : true,
	viewConfig  : {
			forceFit  : true,//, autoFill : true
			emptyText : 'No existen registros para esta instancia.'
		},
	store       	 : [],
	columns     	 : [],
	bbar        : pageMtoEquipo // <--- Barra de paginación
};

var smMtoEquipo = new Ext.grid.RowNumberer();

var saveMtoChange = function (){
	//save changes in the grid
	var modified = Ext.getCmp('MtoEquipo').getStore().getModifiedRecords();//step 1
	if(!Ext.isEmpty(modified)){
		var recordsToSend = [];
		Ext.each(modified, function(record) { //step 2
			recordsToSend.push(Ext.apply(record.data));
		});
		
		Ext.getCmp('MtoEquipo').el.mask('Guardando…', 'x-mask-loading'); //step 3
		Ext.getCmp('MtoEquipo').stopEditing();
	
		recordsToSend = Ext.encode(recordsToSend); //step 4
		
		storeMtoEquipo.on("load",function(Store,records,options,groups){
			Ext.getCmp('MtoEquipo').el.unmask();
			Ext.getCmp('MtoEquipo').getStore().commitChanges();
		});	
		storeMtoEquipo.load({params:{start:0, limit:25, records: recordsToSend}});
	}
	/*console.debug(recordsToSend);*/
 };

var cancelMtoChange = function(){
	//cancel the changes in the grid
	Ext.getCmp('MtoEquipo').getStore().rejectChanges();
 };

var winMtoEquipo = new Ext.Window({
	id     		: 'win-MtoEquipo',
	title		: 'Listado de Transformadores con p&eacute;rdida de vida &uacute;til',
	layout		: 'fit',
	iconCls		: 'icon-eq',
	bodyStyle	: 'padding:1px 0px 0px 0px;',
	width		: 1000,
	height		: 600,
	resizable	: true,
	autoDestroy : true,
	minimizable : true,
	maximizable : true,
	closable	: true,
	closeAction : 'hide',
	autoScroll	: true,
	items:[gridMtoEquipo],
	tbar:{  
        //defaults:{scope:this},  
        items:[  
            {text:'Guardar',iconCls:'icon-saveMto',handler:saveMtoChange},'-',
            {text:'Deshacer',iconCls:'icon-cancelMto',handler:cancelMtoChange}  
        ]  
    }, 
	listeners: {
		minimize: function() {this.toggleCollapse();},
		show: function(){
			storeMtoEquipo.on("load",function(Store,records,options,groups){
				longCol = colMtoEquipo.length;
				for (i=0; i<longCol; i++){colMtoEquipo.shift();}
				colMtoEquipo.push(smMtoEquipo);
				Ext.each(storeMtoEquipo.fields.items,function(item){								  
					switch (item.name) { 
						case 'sce_id':
							colMtoEquipo.push({header:item.header,dataIndex:item.name,hidden:true}); break 
						case 'sce_sobrecarga':
							colMtoEquipo.push({header:item.header,dataIndex:item.name,width:250}); break 
						case 'sce_remplazado':
							colMtoEquipo.push({header:item.header,dataIndex:item.name,sortable:true,editor:{xtype:'combo',triggerAction:'all',store:['No','Si']}}); break 
						default: 
							colMtoEquipo.push({header:item.header,dataIndex:item.name});
					} 		
				});					
				var colModelMtoEquipo = new Ext.grid.ColumnModel({
					columns: colMtoEquipo
					//defaults: {sortable: true, menuDisabled: true,	width: 100},
				});			
				Ext.getCmp('MtoEquipo').reconfigure(storeMtoEquipo,colModelMtoEquipo);				
			});	
			storeMtoEquipo.load({params:{start:0, limit:25, records: Ext.encode([])}});
		}
	}
});

/* ================================================================
 * =======================  Graficas  =============================
 * ================================================================
 */

/*
 * ================  winChart config  =======================
 */
var winChart = new Ext.Window({
	title       : 'Grafica de limites',
	//resizable : false,
	//autoDestroy : true,
	minimizable : true,
	width       : 500,
	height      :350,
	//maskDisabled: true,
	closeAction : 'hide',
	contentEl   : 'chartArea'
	//html: '<p class="details-info">When you select a layout from the tree, additional details will display here.</p>'
	
});
winChart.on('minimize',function(win){
	win.toggleCollapse();
});

/*
 * ================  upForm config  =======================
 */

var msg = function(title, msg, icon){
	Ext.Msg.show({
		title	 : title,
		msg		 : msg,
		minWidth : 200,
		modal	 : true,
		icon	 : icon=='ERROR' ? Ext.Msg.ERROR : Ext.Msg.INFO,
		buttons  : Ext.Msg.OK
	});
};

var txtFile = new Ext.FormPanel({
	fileUpload : true,
	//width: 450,
	frame	   : true,
	//title: 'File Upload Form',
	border 	   : false,
	autoHeight : true,
	bodyStyle  : 'padding: 10px 10px 0 10px;',
	labelWidth : 50,
	defaults   : {
		anchor: '95%',
		allowBlank : false,
		msgTarget  : 'side'
	},
	items      : [{
		xtype	   : 'fileuploadfield',
		id		   : 'form-file',
		fieldLabel : 'Archivo',
		emptyText  : 'Seleccione un archivo...',
		name	   : 'file-txt',
		buttonText : '',
		buttonCfg  : {
			iconCls : 'icon-folderExplore'
		}
	}],
	buttons   : [{
		text    : 'Agregar',
		handler : function(){
			if(txtFile.getForm().isValid()){ 
				txtFile.getForm().submit({	
					url     : 'php/TXT/getTXT.php',
					waitMsg : 'Agregando archivo...',
					success : function(txtFile, o){
						var treeEl = Ext.getCmp('tree-panel');
						var maskTree = new Ext.LoadMask(treeEl.getEl(), {msg:'Cargando...'});
						maskTree.show();
						msg('Exitoso', 'Archivo: "'+o.result.msg+'" cargado al servidor','OK');
						treeEl.on("load",function(){maskTree.hide();});
						treeEl.root.reload();
					},
					failure : function(txtFile, o) {
						msg('ERROR!', 'Error: "'+o.result.msg+'"','ERROR');
					}
				});
			}
		}
	},{
		text    : 'Cancelar',
		handler : function(){
			Ext.getCmp('win-medicion').hide();
			txtFile.getForm().reset();
		}
	}]
});	

var csvFile = new Ext.FormPanel({
	id		   : 'csv-file',
	fileUpload : true,
	frame	   : true,
	border     : false,
	autoHeight : true,
	bodyStyle  : 'padding: 10px 10px 0 10px;',
	labelWidth : 50,
	defaults   : {
		anchor     : '95%',
		allowBlank : false,
		msgTarget  : 'side'
	},
	items      : [
		{   
			xtype      :'textfield',
			fieldLabel :'Campa&#241;a',   
			name       :'txt-campana',  
			emptyText  :'Nombre del cronograma...',   
			id         :'id-campana'  
		},/*
		{   
			xtype	   :'textarea',
			fieldLabel :'Coment',   
			name	   :'txt-comenta',
			id         :'id-coment'
		},*/
		{
			xtype 	   : 'fileuploadfield',
			id	 	   : 'form-file2',
			fieldLabel : 'Archivo',
			emptyText  : 'Seleccione un archivo...',
			name	   : 'file-csv',
			buttonText : '',
			buttonCfg  : {
				iconCls : 'icon-folderExplore'
			}
		}
	],
	buttons: [{
		text	: 'Agregar',
		handler : function(){
			if(csvFile.getForm().isValid()){
				csvFile.getForm().submit({
					url     : 'php/CSV/getCSV.php',
					waitMsg : 'Agregando archivo...',
					success : function(csvFile, o){
						var treeEl = Ext.getCmp('tree-panel');
						var maskTree = new Ext.LoadMask(treeEl.getEl(), {msg:'Cargando...'});
						maskTree.show();
						msg('Exitoso', 'Archivo: "'+o.result.msg+'" cargado al servidor','OK');
						treeEl.on("load",function(){maskTree.hide();});
						treeEl.root.reload();
					},
					failure : function(csvFile, o) {
						msg('ERROR!', 'Error: "'+o.result.msg+'"','ERROR');
					}
				});
			}
		}
	},{
		text    : 'Cancelar',
		handler : function(){
			Ext.getCmp('win-campana').hide();
			csvFile.getForm().reset();
		}
	}]
});	

/*
 * ================  menuStore config  =======================
 */
 
var menuFase = new Ext.ux.menu.StoreMenu({ 
	url       :'php/load/menuStoreFase.php',	
	listeners :{ 
		menushow : function(){ this.loaded=false; }
	} 
});

var storeMenuFase = new Ext.data.JsonStore({
	url : 'php/load/chartFase.php'
});

var menuTipo = new Ext.ux.menu.StoreMenu({ 
	url:'php/load/menuStoreTipo.php', 
	listeners :{ 
		menushow : function(){ this.loaded=false; }
	} 
});

var storeMenuTipo = new Ext.data.JsonStore({
	url : 'php/load/chartTipo.php'
});

var storeMenuOtros = new Ext.data.JsonStore({
	url : 'php/load/chartOtros.php'
});

var menuExportar = new Ext.ux.menu.StoreMenu({ 
	url:'php/load/menuExportar.php', 
	listeners :{ 
		menushow : function(){ this.loaded=false; }
	} 
});

/*
 * ================  dataStore config  =======================
 */
 
var storeData = new Ext.data.JsonStore({
	url : 'php/load/gridCenter.php'
});

var storeResum = new Ext.data.JsonStore({
	url : 'php/load/gridMinMax.php'
});

var storeChart = new Ext.data.JsonStore({
	url : 'php/load/chartCalPro.php'
});

/*
 * ================  page in grid config  =======================
 */

var pageGrid = new Ext.PagingToolbar({
	store       : storeData, // <--grid and PagingToolbar using same store (required)
	displayInfo : true,
	displayMsg  : '{0} - {1} of {2} Muestras',
	emptyMsg    : 'No hay Muestras para mostrar',
	pageSize    : 25
});


pageGrid.on('beforechange',function(bar,params){  
	params.root = root;  
	params.numFase = numFase;
});

/*
 * ================  startPage config  =======================
 */

var start = {
	id        : 'start-panel',
    //title     : 'P&aacute;gina de Inicio',
    //layout    : 'anchor',
    bodyStyle : '',
    items     : [{
		//region    : 'center',
		border    : false,
		//anchor:'right -150',
		contentEl : 'start-div'  // pull existing content from the page
	}/*,{
		region    : 'south',
		border    : false,
		//anchor:'right -200',
		contentEl : 'logo-div'  // pull existing content from the page
	}*/]
};

/*
 * ================  panelGrid config  =======================
 */
 
var panelData = {
	title		: 'Par&aacute;metros de la medici&oacute;n [Todos]',
	layout      : 'fit',
	iconCls		: 'icon-grid',
	region      : 'center',
	border		: true,
	margins     : '0 0 0 0',
	collapsible : false,
	padding     : 0,
	items:[{
		id          : 'gridData',
		xtype       : 'grid',
		layout      : 'fit',
		autoDestroy : true,
		region      : 'center',
		collapsible : false,
		columnLines	: true,
		border      : false,
		//frame       : true,	
		loadMask    : true,//{msg:"Cargando..."},						
		stripeRows  : true,
		viewConfig  : {
			forceFit : true
		},
		store       : [],
		columns     : [],
		bbar        : pageGrid // <--- Barra de paginación
	}]
};

var panelResum = {
	title		: 'Resumen de Par&aacute;metros de la medici&oacute;n',
	layout      : 'fit',
	iconCls		: 'icon-grid',
	region      : 'center',
	border		: true,
	margins     : '0 0 0 0',
	collapsible : false,
	padding     : 0,
	items:[{
		id          : 'gridResum',
		xtype       : 'grid',
		region      : 'center',
		layout      : 'fit',
		collapsible : false,
		border      : false,
		columnLines	: true,
		width       : 10,
		//frame       : true,	
		loadMask    : true,//{msg:"Cargando..."},				
		stripeRows  : true,
		viewConfig  : {
			forceFit : true
		},	
		store       : [],
		columns     : []
	}]
};

/*
 * ================  panelView config  =======================
 */
 
var storeHeader = new Ext.data.JsonStore({
	url : 'php/load/panelInfo.php'
});
storeHeader.on("load",function(Store,records,options,groups){	
	var tpl = new Ext.XTemplate(
		'<tpl for=".">',
			'<div id="info-detalles">',
				'<table class="tabla_resumen" cellspacing="6">',
					'<tbody>',
						'<tr>',
							'<td class="bold" >Serial:</td>',
							'<td align="right">{serial}</td>',
						'</tr>',
						'<tr>',
							'<td class="bold" >Placa:</td>',
							'<td align="right">{placa}</td>',
						'</tr>',
						'<tr>',
							'<td class="bold" >Inicio:</td>',
							'<td align="right">{colocacion}</td>',
						'</tr>',
						'<tr>',
							'<td class="bold" >Punto:</td>',
							'<td align="right">{punto}</td>',
						'</tr>',
					'</tbody>',
				'</table>',
			
			'</div>',
		'</tpl>'
	);						
	tpl.overwrite(Ext.get('dt-info'), storeHeader.data.items[0].data);
});

var panelView = new Ext.Panel({	
	id        : 'panelTpl',
	layout    : 'fit',	
	frame     : true,
	region    :'west',
	floatable : false,
	width     : 250,
	margins   : '0 5 0 0',
	padding   : 1,
	//items     : [datav]
	html   :'<div id="dt-info" style="font-size:12px;"></div>'
	
});

/* ================================================================
 * =================  Calidad de Producto  ========================
 * ================================================================
 */
 
/*
 * ===========  descripCP config  ================
 */

var storeInfoCP = new Ext.data.JsonStore({
	url : 'php/load/calidad_producto/panelInfoCP.php'
});

/*
 * ===========  tablaCP config  ================
 */

var storeMinMaxCP = new Ext.data.JsonStore({
	url : 'php/load/calidad_producto/tablaMinMaxCP.php'
});

/*
 * ===========  contRegCP config  ================
 */

var storeRegCP = new Ext.data.JsonStore({
	url : 'php/load/calidad_producto/panelRegCP.php'
});

/*
 * ================  GridCP config  =======================
 */
 
var storeGridCP = new Ext.data.JsonStore({
	url : 'php/load/calidad_producto/gridRegCP.php'
});

var pageGridCP = new Ext.PagingToolbar({
	store       : storeGridCP, // <--grid and PagingToolbar using same store (required)
	displayInfo : true,
	displayMsg  : '{0} - {1} of {2} Muestras',
	emptyMsg    : 'No hay Muestras para mostrar',
	pageSize    : 25
});


pageGridCP.on('beforechange',function(bar,params){
	params.root = root;
	params.filter = filter;
	params.numFase = numFase;
	params.por_v = por_v;
	params.voltn = voltn;
});

var tablaMinMaxCP = {
	id          	 : 'tablaCP',
	xtype       	 : 'grid',
	autoDestroy 	 : true,
	collapsible 	 : false,
	enableColumnMove : false,
	border      	 : true,
	columnLines		 : true,
	stateful    	 : true,
	loadMask    	 : true,//{msg:"Cargando..."},						
	stripeRows  	 : true,
	viewConfig  : {
			forceFit : true//, autoFill : true
		},
	store       	 : [],
	columns     	 : []
};

var panelGridCP = {
	//xtype		: 'fieldset',
	title		: 'Detalles de perfiles de tensi&oacute;n de Calidad de Producto',
	layout		: 'border',
	border		: true,
	frame		: true,
	margins     : '0 0 0 0',
	collapsible : false,
	padding     : 0,	
	items:[{
		id         : 'radioGroup-CP',
		xtype      : 'radiogroup',
		width      : '100%',
		region	   : 'north',
		cls    : 'x-check-group-alt',
		fieldLabel : 'Filtros de la tabla',
		allowBlank : false,
		items      : [{
			columnWidth: '.33',
			items: [
				{xtype: 'label', text: 'Totales', cls:'x-form-check-group-label', anchor:'-15'},
				{boxLabel: 'Todos', name: 'rb-cust', inputValue: 1, checked: true},
				{boxLabel: 'Penalizados', name: 'rb-cust', inputValue: 2}
			]
		},{
			columnWidth: '.34',
			items: [
				{xtype: 'label', text: 'Registros', cls:'x-form-check-group-label', anchor:'-15'},
				{boxLabel: 'V&aacute;lidos', name: 'rb-cust', inputValue: 3},
				{boxLabel: 'No V&aacute;lidos', name: 'rb-cust', inputValue: 4}
			]
		},{
			columnWidth: '.33',
			items: [
				{xtype: 'label', text: 'Penalizados', cls:'x-form-check-group-label', anchor:'-15'},
				{boxLabel: 'Por Alto', name: 'rb-cust', inputValue: 5},
				{boxLabel: 'Por Bajo', name: 'rb-cust', inputValue: 6}
			]
		}],
		listeners: {change: function (radio){
			//alert(Ext.getCmp('radioGroup-CP').getValue().getGroupValue() + ' was changed. SApo!');
			switch (Ext.getCmp('radioGroup-CP').getValue().getGroupValue()) { 
				case '1':	 filter = 'todos'; break
				case '2':	 filter = 'reg_penalizados'; break
				case '3':	 filter = 'validos'; break
				case '4':	 filter = 'no_validos'; break
				case '5':	 filter = 'reg_pen_alto'; break
				case '6':	 filter = 'reg_pen_bajo'; break
				default: filter = 'todos'; 
			} 
			storeGridCP.load({params:{start:0, limit:25, root:root, filter:filter, numFase:numFase, por_v: por_v, voltn: voltn}});
		}},
	},{
		id          : 'gridCP',
		xtype       : 'grid',
		region      : 'center',
		autoDestroy : true,
		region      : 'center',
		collapsible : false,
		columnLines	: true,
		//height      : 200,
		//autoHeight  : true,
		//autoWidth : true, 
		border      : true,
        stateful    : true,
		loadMask    : true,//{msg:"Cargando..."},						
		stripeRows  : true,
		viewConfig  : {
			forceFit  : true,
			emptyText : 'No existen registros para esta instancia.'
		},
		store       : [],
		columns     : [],
		bbar        : pageGridCP // <--- Barra de paginación
	}]
};

var registros = [
	{
		region    : 'west',
		width     : '40%',
		bodyStyle : 'padding-right:5px;',
		items	  : [{
			xtype		: 'fieldset',
        	collapsible	: true,
			title		: 'Total registros',
			autoHeight	: true,
			html		: '<div id="cp-reg" style="font-size:14px;"></div>'
		},{
			xtype		: 'fieldset',
        	collapsible : true,
			title		: 'Resumen penalizaci&oacute;n',
			html		: '<div id="cp-pen" style="font-size:14px;"></div>'
		},{
			xtype		: 'fieldset',
        	collapsible : true,
			title		: 'Caltulo F.E.B',
			autoHeight  : true,
			html		: '<div id="cp-feb" style="font-size:16px;"></div>'
		},{
			xtype		: 'fieldset',
        	collapsible : true,
			title		: 'Calculo T.P.I',
			autoHeight	: true,
			html	    :'<div id="cp-tip" style="font-size:16px;"></div>'
		},{
			xtype		: 'fieldset',
			collapsible : true,
			title	    : 'Tabla de Fases',
			autoHeight  : true,
			items	    : [tablaMinMaxCP]
			//html:'<div id="cp-min_max" style="font-size:16px;"></div>'
		}]
	}, 
	{
		region    : 'center',
		layout	  : 'fit',
		bodyStyle : 'padding-left:5px;',
		items	  : [panelGridCP]
	}
];

function loadCP(){
	var maskViewReg = new Ext.LoadMask(Ext.getCmp('cp-tab').getEl());
	storeRegCP.on("load",function(Store,records,options,groups){					  
		var tplRegCPReg = new Ext.XTemplate(
			'<div id="reg-detalles">',
				'<p style=" padding:0   5px 0;"><b>Registros totales    : </b>{totales}</p>',
				'<p style=" padding:5px 5px 0;"><b>Registros validos    : </b>{validos}</p>',
				'<p style=" padding:5px 5px 0;"><b>Registros no_validos : </b>{no_validos}</p>',
				'<p style=" padding:5px 5px 0;"></p>',
			'</div>'
		);							
		tplRegCPReg.overwrite(Ext.get('cp-reg'), storeRegCP.data.items[0].data);
		
		var tplRegCPPen = new Ext.XTemplate(
			'<div id="pen-detalles" class="newest">',
				'<p style=" padding:0   5px 0;"><b>Registros penalizados : </b>{penalizados}</p>',
				'<p style=" padding:5px 5px 0;"><b>Registros penalizados por Alto voltaje : </b>{pen_alto}</p>',
				'<p style=" padding:5px 5px 0;"><b>Registros penalizados por Bajo voltaje : </b>{pen_bajo}</p>',
				'<p style=" padding:5px 5px 0;"><b>Registros no_penalizados : </b>{no_penalizados}</p>',
			'</div>'
		);
		tplRegCPPen.overwrite(Ext.get('cp-pen'), storeRegCP.data.items[0].data);
		
		var tplRegCPFeb = new Ext.XTemplate(
			'<div id="feb-detalles class="newest">',
				'<p style=" padding:0   5px 0;"><b>F.E.B : </b>{FEB} ',
				'<tpl if="3 <= FEB">',
					'<b style="color:#F00">  *** PENALIZ&Oacute;!!! ***  </b></p>',
				'</tpl>',
			'</div>'
		);
		tplRegCPFeb.overwrite(Ext.get('cp-feb'), storeRegCP.data.items[0].data);
		
		var tplRegCPTip = new Ext.XTemplate(
			'<div id="tpi-detalles class="newest">',
				'<p style=" padding:0   5px 0;"><b>T.P.I : </b>{TPI} minutos.</p>',
			'</div>'
		);
		tplRegCPTip.overwrite(Ext.get('cp-tip'), storeRegCP.data.items[0].data);
	});
	maskViewReg.show();		
	storeRegCP.load({
		params:{root:root, numFase:numFase, por_v: por_v, voltn: voltn},
		callback: function(){maskViewReg.hide();}
	});
	
	storeGridCP.on("load",function(Store,records,options,groups){
		longCol = colGridCP.length;
		for (i=0; i<longCol; i++){colGridCP.shift();}
		
		Ext.each(storeGridCP.fields.items,function(item){
			switch (item.name) { 
				case 'reg_v1':
				case 'reg_v2':
				case 'reg_v3':
				case 'reg_vp':
					colGridCP.push({
						header:item.header,
						dataIndex:item.name,
						renderer: function(value, metaData, record, rowIndex, colIndex, store){
							return value!=null?value==0?null:value <= 120*0.1?'<p style="color:#f00">'+value+'</p>':'<p style="color:#000">'+value+'</p>':null;
						}
					});   	
					break
				default: 
					colGridCP.push({header:item.header,dataIndex:item.name});
			} 
		});					
		var colModel = new Ext.grid.ColumnModel({
			columns: colGridCP /*defaults: {sortable: true, menuDisabled: true,	width: 100},*/
		});			
		Ext.getCmp('gridCP').reconfigure(storeGridCP,colModel);		
	});
	storeGridCP.load({params:{start:0, limit:25, root:root, numFase:numFase, por_v: por_v, voltn: voltn}});
}

function calcLimit(){
	Ext.getCmp('txt-li-sup').setValue(roundNumber(Ext.getCmp('cmb-vn').getValue()*((100+Ext.getCmp('cmb-pc').getValue())/100),3)); 
	Ext.getCmp('txt-li-inf').setValue(roundNumber(Ext.getCmp('cmb-vn').getValue()*((100-Ext.getCmp('cmb-pc').getValue())/100),3)); 
}

var dataVn=[120,208,240,460,480];
var dataPc=[10,9,8,7,6,5];

var confiCP = new Ext.FormPanel({
	region	   : 'east',
	labelAlign : 'top',
	frame	   : true,
	border	   : false,
	bodyStyle  :'padding:5px 5px 0',
	//width	   : '35%',
	items: [{
		layout :'column',
		border	   : false,
		items  :[{
			columnWidth : .5,
			layout      : 'form',
			border	   : false,
			items	    : [{
				xtype		   : 'combo',
				fieldLabel	   : 'Volt Nominal',
				id			   : 'cmb-vn',
				name		   : 'cmb-voltn',
				store		   : dataVn,
				forceSelection : true,
				width		   : 60,
				value		   : 120,
				triggerAction  : 'all',
				editable	   :false,
				anchor		   :'95%',
				listeners 	   :{ 
					select : function(cmb,record,index){
						calcLimit();
					}
				}
			},{
				xtype	   :'textfield',
				fieldLabel : 'Limite Sup',
				id		   : 'txt-li-sup',
				readOnly   : true,
				value	   : 132,
				anchor	   : '95%'
			}]
		},{
			columnWidth :.5,
			layout		: 'form',
			border	   : false,
			items		: [{
				xtype		   : 'combo',
				fieldLabel	   : 'Var [%]',
				id			   : 'cmb-pc',
				name		   : 'cmb-porc',
				store		   : dataPc,
				forceSelection : true,
				width		   : 60,
				value		   : 10,
				triggerAction  : 'all',
				editable	   : false,
				anchor		   :'95%',
				listeners 	   :{ 
					select : function(cmb,record,index){
						calcLimit();
					}
				}
			},{
				xtype	   : 'textfield',
				fieldLabel : 'Limite Inf',
				id		   : 'txt-li-inf',
				readOnly   : true,
				value	   : 108,
				anchor	   :'95%'
			}]
		}]
	}],
	buttons   : [{
		text    : 'Actualizar',
		handler : function(){
			voltn = Ext.getCmp('cmb-vn').getValue();
			por_v = Ext.getCmp('cmb-pc').getValue();
			loadCP();
		}
	}]
});

/* ================================================================
 * ====================  Mantenimiento  ===========================
 * ================================================================
 */

/*
 * ===========  contRegCP config  ================
 */

var storeInfoMT = new Ext.data.JsonStore({
	url : 'php/load/mantenimiento/infoMT.php'
});

/*
 * ================  tabla KvaxFase  =======================
 */
 
var storeKvaFase = new Ext.data.JsonStore({
	url : 'php/load/mantenimiento/gridKvaFase.php'
});

var tablaKavFase = {
	id          	 : 'KvaFaseMT',
	title			 : 'Capacidad total de la Unidad',
	xtype       	 : 'grid',
	autoDestroy 	 : true,
	collapsible 	 : false,
	columnLines		 : true,
	enableColumnMove : false,
	border      	 : false,
	stateful    	 : true,
	loadMask    	 : true,//{msg:"Cargando..."},						
	stripeRows  	 : true,
	viewConfig  : {
			forceFit : true//, autoFill : true
		},
	store       	 : [],
	columns     	 : []
};

/*
 * ================  tabla UsuxKva  =======================
 */
 
var storeUsuKva = new Ext.data.JsonStore({
	url : 'php/load/mantenimiento/gridUsuKva.php'
});

var tablaUsuKav = {
	id          	 : 'usuKvaMT',
	title			 : 'Fases de conexi&oacute;n de Usuarios',
	xtype       	 : 'grid',
	autoDestroy 	 : true,
	collapsible 	 : false,
	columnLines		 : true,
	enableColumnMove : false,
	border      	 : false,
	stateful    	 : true,
	loadMask    	 : true,//{msg:"Cargando..."},						
	stripeRows  	 : true,
	viewConfig  : {
			forceFit : true//, autoFill : true
		},
	store       	 : [],
	columns     	 : []
};

/*
 * ==============  tabla Corriente  ====================
 */
 
var storeCorriMT = new Ext.data.JsonStore({
	url : 'php/load/mantenimiento/gridCorriMT.php'
});

var tablaCorriMT = {
	id          	 : 'CorriMT',
	title			 : 'Resumen de Corrientes',
	xtype       	 : 'grid',
	autoDestroy 	 : true,
	collapsible 	 : false,
	columnLines		 : true,
	enableColumnMove : false,
	border      	 : false,
			stateful    	 : true,
	loadMask    	 : true,//{msg:"Cargando..."},						
	stripeRows  	 : true,
	viewConfig  : {
			forceFit : true//, autoFill : true
		},
	store       	 : [],
	columns     	 : []
};

/*
 * ============  gridStatus UT-EQ  ==================
 */
 
var storeStatusMT = new Ext.data.JsonStore({
	url : 'php/load/mantenimiento/gridStatusMT.php'
});

var pageStatusMT = new Ext.PagingToolbar({
	store       : storeStatusMT, // <--grid and PagingToolbar using same store (required)
	displayInfo : true,
	displayMsg  : '{0} - {1} of {2} Muestras',
	emptyMsg    : 'No hay Muestras para mostrar',
	pageSize    : 25
});


pageStatusMT.on('beforechange',function(bar,params){  
	params.root = root; // id de la muestra
	params.idUT = idUT; // id de Utransformadores o placa
	params.numFase = numFase; // numero de fases del Utransformadores
	params.reg_s = reg_s; // indica que Potencia se va a evaluar: [St, S1, S2, S3]
	params.statsUE = statsUE; // indica por que se va a filtar las muestras: [SC, RG, SU]
});

var gridStatusMT = {
	id          	 : 'StatusMT',
	xtype       	 : 'grid',
	autoDestroy 	 : true,
	layout           : 'fit',
	collapsible 	 : false,
	columnLines		 : true,
	enableColumnMove : false,
	border      	 : false,
	stateful    	 : true,
	loadMask    	 : true,//{msg:"Cargando..."},						
	stripeRows  	 : true,
	viewConfig  : {
			forceFit : true,//, autoFill : true
			emptyText : 'No existen registros para esta instancia.'
		},
	store       	 : [],
	columns     	 : [],
	bbar        : pageStatusMT // <--- Barra de paginación
};

var storeComboMT = new Ext.data.JsonStore({
	url : 'php/load/mantenimiento/comboMT.php'
});

var comboMT = new Ext.form.ComboBox({
	fieldLabel    : 'Fases o Totales',  
	id			  : 'mycb',
	name          : 'cmbMT',  
	store		  : storeComboMT, //asignandole el store  
	emptyText     : 'pick one DB...',  
	triggerAction : 'all', 
	width		  : 100,
	editable	  : false,  
	mode		  : 'local',
	value		  : 'ST',
	displayField  : 'name',  
	valueField	  : 'name',
	listeners :{ 
		select : function(cmb,record,index){
			reg_s = record.get('desc');
			storeStatusMT.load({params:{root:root, idUT:idUT, numFase:numFase, reg_s:reg_s, statsUE:statsUE}});
			Ext.Ajax.request({
				url : 'php/load/mantenimiento/chartTurnos.php' , 
				params : {
					root:root, 
					idUT:idUT, 
					numFase:numFase, 
					reg_s:reg_s, 
					statsUE:statsUE, 
					h1:h1,
					h2:h2,
					h3:h3
				},
				method: 'POST',
				success: function ( result, request ) {
					var r_ChartMT = result.responseText;
					var d_ChartMT = new Array;
					d_ChartMT.push(	[r_ChartMT.split(';')[0].split(',')[0].trim(),parseFloat(r_ChartMT.split(';')[0].split(',')[1].trim())],
									[r_ChartMT.split(';')[1].split(',')[0].trim(),parseFloat(r_ChartMT.split(';')[1].split(',')[1].trim())],
									[r_ChartMT.split(';')[2].split(',')[0].trim(),parseFloat(r_ChartMT.split(';')[2].split(',')[1].trim())]);
					Ext.get('chartMT-details').remove();
					Ext.get('chartMT-padre').createChild({id:'chartMT-details', style: {'height':'100%', 'width':'100%'}});
					$().statusCarga('chartMT-details',d_ChartMT.sort(),parseFloat(r_ChartMT.split(';')[3].split(',')[1].trim()));
				},
				failure: function ( result, request)  {$().statusCarga('chartMT-details',[['N/A',0],['N/A',0],['N/A',0]],parseFloat(r_ChartMT.split(';')[3].split(',')[1].trim()));} 
			});
		}
	}
});


var storeCmbH1 = new Ext.data.JsonStore({
	url : 'php/load/mantenimiento/cbHorasMT.php'
});

var storeCmbH2 = new Ext.data.JsonStore({
	url : 'php/load/mantenimiento/cbHorasMT.php'
});

var dHour=['00:00','01:00','02:00','03:00','04:00','05:00','06:00','07:00','08:00','09:00','10:00','11:00',
		  '12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00','23:00'];

function sumaHora(F_ini,Inc){
	if((F_ini + Inc) > 24){
		var result = Inc - (24 - F_ini);
		return result < 10 ? '0'+result+':00' : result+':00';
	}else{
		var result = F_ini + Inc;
		return result < 10 ? '0'+result+':00' : result+':00';
	}
};

var confiTurn = new Ext.FormPanel({
	region	   : 'east',
	labelAlign : 'top',
	//frame	   : true,
	border	   : false,
	bodyStyle  :'padding:5px 5px 0',
	width	   : '35%',
	items: [{
		layout :'column',
		border	   : false,
		items  :[{
			columnWidth : .5,
			layout      : 'form',
			border	   : false,
			items	    : [{
				xtype		   : 'combo',
				store		   : dHour,
				id			   : 'cmb-T1',
				fieldLabel	   : 'Inicio 1er turno',
				name		   : 'cmb-data',
				forceSelection : true,
				width		   : 60,
				emptyText	   :'hora',
				triggerAction  : 'all',
				//hideTrigger:true,
				editable	   :false,
				anchor		   :'95%',
				listeners 	   :{ 
					select : function(cmb,record,index){
						var value = record.get('field1');
						confiTurn.getForm().reset();
						Ext.getCmp('cmb-T1').setValue(value); 
					}
				}
			},{
				xtype	   :'textfield',
				id		   : 'cmb-T2',
				fieldLabel : 'Inicio 2do turno',
				name	   : 'ini_2',
				emptyText  :'hora',
				readOnly   : true,
				//disabled: true,
				anchor	   :'95%'
			},{
				xtype	   :'textfield',
				id		   : 'cmb-T3',
				fieldLabel : 'Inicio 3er turno',
				name	   : 'ini_3',
				emptyText  :'hora',
				readOnly   : true,
				//disabled: true,
				anchor	   :'95%'
			}]
		},{
			columnWidth :.5,
			layout		: 'form',
			border	   : false,
			items		: [{
				xtype		  :'combo',
				store		  : storeCmbH1,
				id			  : 'cmb-H1',
				valueField	  : 'value',
				displayField  : 'name',
				triggerAction : 'all',
				fieldLabel	  : 'Tiempo 1er turno',
				emptyText	   :'tiempo',
				editable	  : false,
				//disabled	  : true,
				anchor		  :'95%',
				listeners 	  :{ 
					select : function(cmb,record,index){
						Ext.getCmp('cmb-T2').setValue(sumaHora(parseInt(Ext.getCmp('cmb-T1').getValue().split(':')[0]),record.get('value')));
						Ext.getCmp('cmb-H2').enable();         
						Ext.getCmp('cmb-H2').clearValue();     
						Ext.getCmp('cmb-H3').setValue('');     
						storeCmbH2.load({          
							params:{ 
								depenH:1,  
								restoH:record.get('value')   
							}  
						}); 
					}
				}
			},{
				xtype		  : 'combo',
				store		  : storeCmbH2,
				id			  : 'cmb-H2',
				valueField	  : 'value',
				displayField  : 'name',
				triggerAction : 'all',
				emptyText	   :'tiempo',
				fieldLabel	  : 'Tiempo 2do turno',
				editable	  : false,
				disabled	  : true,
    			mode		  : 'local',
				allowBlank	  : false,
				anchor		  : '95%',
				listeners 	  :{ 
					select : function(cmb,record,index){ //step 1  
						Ext.getCmp('cmb-T3').setValue(sumaHora(parseInt(Ext.getCmp('cmb-T2').getValue().split(':')[0]),record.get('value')));
						Ext.getCmp('cmb-H3').enable();     
						Ext.getCmp('cmb-H3').setValue((24-(Ext.getCmp('cmb-H1').getValue()+Ext.getCmp('cmb-H2').getValue()))+' H');     
					}
				}
			},{
				xtype	   : 'textfield',
				id		   : 'cmb-H3',
				fieldLabel : 'Tiempo 3er turno',
				emptyText  :'tiempo',
				allowBlank : false,
				readOnly   : true,
				//disabled   : true,
				anchor	   :'95%'
			}]
		}]
	}],
	buttons   : [{
		text    : 'Actualizar',
		handler : function(){
			if(Ext.getCmp('cmb-H3').getValue().length){
				h1 = Ext.getCmp('cmb-T1').getValue().split(':')[0];
				h2 = Ext.getCmp('cmb-T2').getValue().split(':')[0];
				h3 = Ext.getCmp('cmb-T3').getValue().split(':')[0];
				Ext.Ajax.request({
					url : 'php/load/mantenimiento/chartTurnos.php' , 
					params : {
						root:root, 
						idUT:idUT, 
						numFase:numFase, 
						reg_s:reg_s, 
						statsUE:statsUE, 
						h1:h1,
						h2:h2,
						h3:h3
					},
					method: 'POST',
					success: function ( result, request ) {
						var r_ChartMT = result.responseText;
						var d_ChartMT = new Array;
						d_ChartMT.push(	[r_ChartMT.split(';')[0].split(',')[0].trim(),parseFloat(r_ChartMT.split(';')[0].split(',')[1].trim())],
										[r_ChartMT.split(';')[1].split(',')[0].trim(),parseFloat(r_ChartMT.split(';')[1].split(',')[1].trim())],
										[r_ChartMT.split(';')[2].split(',')[0].trim(),parseFloat(r_ChartMT.split(';')[2].split(',')[1].trim())]);
						Ext.get('chartMT-details').remove();
						Ext.get('chartMT-padre').createChild({id:'chartMT-details', style: {'height':'100%', 'width':'100%'}});
						$().statusCarga('chartMT-details',d_ChartMT.sort(),parseFloat(r_ChartMT.split(';')[3].split(',')[1].trim()));
					},
					failure: function ( result, request)  {$().statusCarga('chartMT-details',[['N/A',0],['N/A',0],['N/A',0]],parseFloat(r_ChartMT.split(';')[3].split(',')[1].trim()));} 
				});
			}
		}
	}]
});

/*
 * ==============  tabla Factores  ====================
 */
 
var storeFactorMT = new Ext.data.JsonStore({
	url : 'php/load/mantenimiento/gridFactorMT.php'
});

var tablaFactorMT = {
	id          	 : 'FactorMT',
	title			 : 'Factores de Cargabilidad',
	xtype       	 : 'grid',
	autoHeight		 : true,
	autoDestroy 	 : true,
	collapsible 	 : false,
	columnLines		 : true,
	enableColumnMove : false,
	border      	 : false,
	stateful    	 : true,
	loadMask    	 : true,//{msg:"Cargando..."},						
	stripeRows  	 : true,
	viewConfig  : {
			forceFit : true//, autoFill : true
		},
	store       	 : [],
	columns     	 : []
};

/*
 * ================  AbsoluteLayout config  =======================
 */

var absolute = {
			id        : 'tab-container',
			xtype     : 'tabpanel',
			plain     : true,
			border    : false,
			region    : 'center',
			margins   : '0 0 0 0',
			padding   : '1 1 1 1',
			activeTab : 0,
			items     : [
				{
					title    : 'Datos de la Medici&oacute;n',					
					cls      : 'inner-tab-custom', // custom styles in layout-browser.css
					layout   : 'border',
					// Make sure IE can still calculate dimensions after a resize when the tab is not active.
					// With display mode, if the tab is rendered but hidden, IE will mess up the layout on show:
					hideMode : Ext.isIE ? 'offsets' : 'display',
					//bodyBorder: false,
					defaults : {
						split        : true,
						animFloat    : false,
						autoHide     : false,
						useSplitTips : true,
						bodyStyle    : 'padding:15px',
					},
					tbar: [{
						xtype    : 'splitbutton',
						text     : 'Por Fase', 
						iconCls  : 'icon-chart',
						renderTo : 'external-menu',
						menu     : menuFase,
						handler  : function(){ 
							var divID = "ext-comp-" + (++Ext.Component.AUTO_ID);
							var winID = "ext-comp-" + (++Ext.Component.AUTO_ID);
							new Ext.Window({
								id: winID,
								title: 'Grafica por Tipos',
								autoDestroy : true,
								minimizable: true,
								width: 765,
								resizable: false,
								height:400,
								closeAction : 'hide',
								items:[
									new Ext.Panel({
										border		: false,
										margins     : '0 0 0 0',
										collapsible : false,
										padding     : 5,
										width       : 750,
										height      : 370,
										html : '<div id="'+divID+'" style=""></div>',
										buttons: [{
											text: 'Zoom',
											handler: function(){
												chartFase.resetZoom();
											}
										}]
									})
								]
							}).show();
							
							Ext.getCmp(winID).on('minimize',function(win){
								win.toggleCollapse();
							});
							
							var winView = new Ext.LoadMask(Ext.getCmp(winID).getEl(), {msg:'Cargando...'});
							
							storeMenuFase.on("load",function(Store,records,options,groups){
								storeMenuFase.each(function(record){
									$().grupoFase(divID,
										typeof(record.get('cV')) !='undefined'?record.get('cV') :null,
										typeof(record.get('cI')) !='undefined'?record.get('cI') :null,
										typeof(record.get('cS')) !='undefined'?record.get('cS') :null,
										typeof(record.get('cPf'))!='undefined'?record.get('cPf'):null,
										cFase
									);
								});	
							});	
							
							winView.show();			
							storeMenuFase.load({
								params:{root:root, numFase: numFase, cFase:cFase, cV:cV, cI:cI, cS:cS, cPf:cPf},
								callback: function(){
									winView.hide();	
								}
							});	
						}/*,
						listeners: {
							beforeshow: function() {this.loaded=false;}
						}*/
					},'-',{
						xtype    : 'splitbutton',
						text     : 'Por Par&aacute;metros', 
						iconCls  : 'icon-chart',
						renderTo : 'external-menu',
						menu     : menuTipo,
						handler  : function(){ 
							var divID = "ext-comp-" + (++Ext.Component.AUTO_ID);
							var winID = "ext-comp-" + (++Ext.Component.AUTO_ID);
							new Ext.Window({
								id: winID,
								title: 'Grafica por Tipos',
								autoDestroy : true,
								minimizable: true,
								width: 765,
								resizable: false,
								height:400,
								closeAction : 'hide',
								items:[
									new Ext.Panel({
										border		: false,
										margins     : '0 0 0 0',
										collapsible : false,
										padding     : 5,
										width       : 750,
										height      : 370,
										html : '<div id="'+divID+'" style=""></div>',
										buttons: [{
											text: 'Zoom',
											handler: function(){
												chartTipo.resetZoom();
											}
										}]
									})
								]
							}).show();
							
							Ext.getCmp(winID).on('minimize',function(win){
								win.toggleCollapse();
							});
							
							var winView = new Ext.LoadMask(Ext.getCmp(winID).getEl(), {msg:'Cargando...'});
							
							storeMenuTipo.on("load",function(Store,records,options,groups){
								storeMenuTipo.each(function(record){
									$().grupoTipo(divID,
										typeof(record.get('f1'))!='undefined'?record.get('f1') :null,
										typeof(record.get('f2'))!='undefined'?record.get('f2') :null,
										typeof(record.get('f3'))!='undefined'?record.get('f3') :null,
										cTipo
									);	
								});	
							});	
							
							winView.show();			
							storeMenuTipo.load({
								params:{root:root, numFase: numFase, cTipo:cTipo, f1:f1, f2:f2, f3:f3},
								callback: function(){
									winView.hide();	
								}
							});	
						}/*,
						listeners: {
							beforeshow: function() {this.loaded=false;}
						}*/
					},'-',{
						xtype    : 'splitbutton',
						text     : 'Otros', 
						iconCls  : 'icon-chart',
						//renderTo : 'external-menu',
						menu     : [
							{text : '[V]  Voltaje promedio', iconcls : 'not_yet_defined', checked : true, group : 'otros', handler : function(menuItem, choice){Otros='Vp';}},
							{text : '[%]  Desbalance', iconcls : 'not_yet_defined', checked : false, group : 'otros', handler : function(menuItem, choice){Otros='Db';}},
							{text : '[VA]  Potencia total', iconcls : 'not_yet_defined', checked : false, group : 'otros', handler : function(menuItem, choice){Otros='St';}},
							{text : '[%]  FPotencia total', iconcls : 'not_yet_defined', checked : false, group : 'otros', handler : function(menuItem, choice){Otros='PFt';}},
							{text : '[WH] Energia consumida', iconcls : 'not_yet_defined', checked : false, group : 'otros', handler : function(menuItem, choice){Otros='Wt';}}
						],
						handler  : function(){ 
							var divID = "ext-comp-" + (++Ext.Component.AUTO_ID);
							var winID = "ext-comp-" + (++Ext.Component.AUTO_ID);
							new Ext.Window({
								id: winID,
								title: 'Grafica por Tipos',
								autoDestroy : true,
								minimizable: true,
								width: 765,
								resizable: false,
								height:400,
								closeAction : 'hide',
								items:[
									new Ext.Panel({
										border		: false,
										margins     : '0 0 0 0',
										collapsible : false,
										padding     : 5,
										width       : 750,
										height      : 370,
										html : '<div id="'+divID+'" style=""></div>',
										buttons: [{
											text: 'Zoom',
											handler: function(){
												chartTipo.resetZoom();
											}
										}]
									})
								]
							}).show();
							
							Ext.getCmp(winID).on('minimize',function(win){
								win.toggleCollapse();
							});
							
							var winView = new Ext.LoadMask(Ext.getCmp(winID).getEl(), {msg:'Cargando...'});
							
							storeMenuOtros.on("load",function(Store,records,options,groups){
								storeMenuOtros.each(function(record){
									$().miscelaneos(divID,record.get('Otros'),Otros);
								});	
							});	
							
							winView.show();			
							storeMenuOtros.load({
								params:{root:root, numFase: numFase, Otros:Otros},
								callback: function(){
									winView.hide();	
								}
							});	
						}
					},'-',{
						xtype    : 'splitbutton',
						text     : 'Exportar (txt)', 
						iconCls  : 'icon-muestra-save', 
						renderTo : 'external-menu',
						menu     : menuExportar,
						handler  : function(){
							var paramsFormato = numFase==3 ? 'root='+root+'&numFase='+numFase+'&v1='+eV1+'&v2='+eV2+'&v3='+eV3+'&i1='+eI1+'&i2='+eI2+'&i3='+eI3+'&s1='+eS1+'&s2='+eS2+'&s3='+eS3+'&st='+eST+'&pf1='+ePF1+'&pf2='+ePF2+'&pf3='+ePF3+'&pft='+ePFT+'&wt='+eWT : 'root='+root+'&numFase='+numFase+'&v1='+eV1+'&v2='+eV2+'&i1='+eI1+'&i2='+eI2+'&s1='+eS1+'&s2='+eS2+'&st='+eST+'&pf1='+ePF1+'&pf2='+ePF2+'&pft='+ePFT+'&wt='+eWT;
							/*console.debug(paramsFormato);*/
							$.download('php/download/downloadTXT.php',paramsFormato);
						}/*,
						listeners: {
							beforeshow: function() {this.loaded=false;}
						}*/
					}],
					items    : [panelData, {
							region      : 'north',//'south',
							layout      : 'border',
							height      : 120,
							minSize     : 75,
							maxSize     : 250,
							padding     : 0,
							margins     : '0 0 0 0',
							cmargins    : '5 0 0 0',
							border      : false,
							items       : [ panelView, panelResum ]
						}
					]
				},
				{
					id:'cp-tab',
					title    : 'Calidad de Producto',
					cls      : 'inner-tab-custom', // custom styles in layout-browser.css
					layout   : 'border',
					padding   : '2 0 0 0',
					// Make sure IE can still calculate dimensions after a resize when the tab is not active.
					// With display mode, if the tab is rendered but hidden, IE will mess up the layout on show:
					hideMode  : Ext.isIE ? 'offsets' : 'display',
					defaults  : {
						animFloat    : false,
						autoHide     : false,
						useSplitTips : true,
					},
					tbar: [{
						text: 'Limites de tesi&oacute;n', 
						iconCls: 'icon-chart', 
						handler: function(){
							var divID = "ext-comp-" + (++Ext.Component.AUTO_ID);
							var winID = "ext-comp-" + (++Ext.Component.AUTO_ID);
							new Ext.Window({
								id: winID,
								title: 'Grafica de limites',
								autoDestroy : true,
								minimizable: true,
								width: 765,
								resizable: false,
								height:400,
								closeAction : 'hide',
								items:[
									new Ext.Panel({
										border		: false,
										margins     : '0 0 0 0',
										collapsible : false,
										padding     : 5,
										width       : 750,
										height      : 370,
										html : '<div id="'+divID+'" style=""></div>',
										buttons: [{
											text: 'Zoom',
											handler: function(){
												chartCP.resetZoom();
											}
										}]
									})
								]
							}).show();
							
							Ext.getCmp(winID).on('minimize',function(win){
								win.toggleCollapse();
							});
							
							var winView = new Ext.LoadMask(Ext.getCmp(winID).getEl(), {msg:'Cargando...'});
							
							storeChart.on("load",function(Store,records,options,groups){
								storeChart.each(function(record){
									$().limitVolt(divID,record.get('vProm'),record.get('iniDate'),record.get('finDate'),
												  roundNumber(voltn*((100+por_v)/100),3),
												  roundNumber(voltn*((100-por_v)/100),3));
								});	
							});	
							
							winView.show();			
							storeChart.load({
								params:{root:root, numFase:numFase},
								callback: function(){
									winView.hide();	
								}
							});									
						}
					},'-',{
						text: 'Formato Ministerio (txt)', 
						iconCls: 'icon-muestra-save', 
						handler: function(){
							var paramsFormato = numFase==3 ? 'root='+root+'&numFase='+numFase+'&v1=1&v2=1&v3=1&st=1&wt=1' : 'root='+root+'&numFase='+numFase+'&v1=1&v2=1&st=1&wt=1';
							//console.debug(paramsFormato);
							$.download('php/download/downloadTXT.php',paramsFormato);
						}
					}],
					listeners : {
						activate: function (tab){
							if(storeInfoCP.getCount() == 0){
								storeInfoCP.on("load",function(Store,records,options,groups){					  
									var tplInfoCP = new Ext.XTemplate(
										'<div id="info-detalles">',
											'<table class="tabla_resumen" cellspacing="6">',
												'<tbody>',
													'<tr>',
														'<td class="bold" >Punto    :</td>',
														'<td align="right">#{cro_row}</td>',
													'</tr>',
													'<tr>',
														'<td class="bold" >Placa    :</td>',
														'<td align="right">{cro_placa}</td>',
													'</tr>',
													'<tr>',
														'<td class="bold" >Serial   :</td>',
														'<td align="right">{cro_serial}</td>',
													'</tr>',
													'<tr>',
														'<td class="bold" >F_Inicio :</td>',
														'<td align="right">{ini_date}</td>',
													'</tr>',
													'<tr>',
														'<td class="bold" >H_Inicio :</td>',
														'<td align="right">{ini_hour}</td>',
													'</tr>',
													'<tr>',
														'<td class="bold" >F_Retiro :</td>',
														'<td align="right">{fin_date}</td>',
													'</tr>',
													'<tr>',
														'<td class="bold" >H_Retiro :</td>',
														'<td align="right">{fin_hour}</td>',
													'</tr>',
												'</tbody>',
											'</table>',
										'</div>'
									);							
									tplInfoCP.overwrite(Ext.get('cp-info'), storeInfoCP.data.items[0].data);
								});
								storeInfoCP.load({params:{root:root}});
							}
							if(storeRegCP.getCount() == 0){
								var maskViewReg = new Ext.LoadMask(tab.getEl());
								storeRegCP.on("load",function(Store,records,options,groups){					  
									var tplRegCPReg = new Ext.XTemplate(
										'<div id="reg-detalles">',
											'<p style=" padding:0   5px 0;"><b>Registros totales    : </b>{totales}</p>',
											'<p style=" padding:5px 5px 0;"><b>Registros validos    : </b>{validos}</p>',
											'<p style=" padding:5px 5px 0;"><b>Registros no_validos : </b>{no_validos}</p>',
											'<p style=" padding:5px 5px 0;"></p>',
										'</div>'
									);							
									tplRegCPReg.overwrite(Ext.get('cp-reg'), storeRegCP.data.items[0].data);
									
									var tplRegCPPen = new Ext.XTemplate(
										'<div id="pen-detalles" class="newest">',
											'<p style=" padding:0   5px 0;"><b>Registros penalizados : </b>{penalizados}</p>',
											'<p style=" padding:5px 5px 0;"><b>Registros penalizados por Alto voltaje : </b>{pen_alto}</p>',
											'<p style=" padding:5px 5px 0;"><b>Registros penalizados por Bajo voltaje : </b>{pen_bajo}</p>',
											'<p style=" padding:5px 5px 0;"><b>Registros no_penalizados : </b>{no_penalizados}</p>',
										'</div>'
									);
									tplRegCPPen.overwrite(Ext.get('cp-pen'), storeRegCP.data.items[0].data);
									
									var tplRegCPFeb = new Ext.XTemplate(
										'<div id="feb-detalles class="newest">',
											'<p style=" padding:0   5px 0;"><b>F.E.B : </b>{FEB} ',
											'<tpl if="3 <= FEB">',
												'<b style="color:#F00">  *** PENALIZ&Oacute;!!! ***  </b></p>',
											'</tpl>',
										'</div>'
									);
									tplRegCPFeb.overwrite(Ext.get('cp-feb'), storeRegCP.data.items[0].data);
									
									var tplRegCPTip = new Ext.XTemplate(
										'<div id="tpi-detalles class="newest">',
											'<p style=" padding:0   5px 0;"><b>T.P.I : </b>{TPI} minutos.</p>',
										'</div>'
									);
									tplRegCPTip.overwrite(Ext.get('cp-tip'), storeRegCP.data.items[0].data);
								});
								maskViewReg.show();		
								storeRegCP.load({
									params:{root:root, numFase:numFase, por_v: por_v, voltn: voltn},
									callback: function(){maskViewReg.hide();}
								});
							}
							if(storeMinMaxCP.getCount() == 0){
								storeMinMaxCP.on("load",function(Store,records,options,groups){			
									longCol = colTablaCP.length;
									for (i=0; i<longCol; i++){colTablaCP.shift();}
									
									Ext.each(storeMinMaxCP.fields.items,function(item){
										switch (item.name) { 
											case 'col_2':
											case 'col_3':
											case 'col_4':
											case 'col_5':
												colTablaCP.push({
													header:item.header,
													dataIndex:item.name,
													renderer: function(value, metaData, record, rowIndex, colIndex, store){
														return value!=null?value <= 120*0.1?'<p style="color:#f00">'+value+'</p>':'<p style="color:#000">'+value+'</p>':null;
													}
												});   	
												break 
											case 'col_1':
												colTablaCP.push({header:item.header,dataIndex:item.name,css:'background-color: #e2e6e7;', width: 50});	
												break
											default: 
												colTablaCP.push({header:item.header,dataIndex:item.name});
										} 
									});					
									var colModelTablaCP = new Ext.grid.ColumnModel({
										columns: colTablaCP, defaults: {sortable: false, menuDisabled: true}
									});			
									Ext.getCmp('tablaCP').reconfigure(storeMinMaxCP,colModelTablaCP);	
								});
								storeMinMaxCP.load({params:{root:root, numFase:numFase}});
							}
							if(storeGridCP.getCount() == 0){
								storeGridCP.on("load",function(Store,records,options,groups){
									longCol = colGridCP.length;
									for (i=0; i<longCol; i++){colGridCP.shift();}
									
									Ext.each(storeGridCP.fields.items,function(item){
										switch (item.name) { 
											case 'reg_v1':
											case 'reg_v2':
											case 'reg_v3':
											case 'reg_vp':
												colGridCP.push({
													header:item.header,
													dataIndex:item.name,
													renderer: function(value, metaData, record, rowIndex, colIndex, store){
														return value!=null?value==0?null:value <= 120*0.1?'<p style="color:#f00">'+value+'</p>':'<p style="color:#000">'+value+'</p>':null;
													}
												});   	
												break
											default: 
												colGridCP.push({header:item.header,dataIndex:item.name});
										} 
									});					
									var colModel = new Ext.grid.ColumnModel({
										columns: colGridCP /*defaults: {sortable: true, menuDisabled: true,	width: 100},*/
									});			
									Ext.getCmp('gridCP').reconfigure(storeGridCP,colModel);		
								});
								storeGridCP.load({params:{start:0, limit:25, root:root, numFase:numFase, por_v: por_v, voltn: voltn}});
							}
						}
					},
					items    : [{
						
							region  : 'west',
							layout  : 'border',
							//collapsible : true,
							width   : 200,
							margins : '1 1 0 0',
							border  : false,
							//bodyStyle   :'padding:10px;',
							items   : [{
								region : 'north',
								title  : 'Descripcion',
								frame  :true,
								split  :true,
							    height : 300,
								html   :'<div id="cp-info" style="font-size:12px;"></div>'
							},{
								id 		: 'cp-confi',
								iconCls	: 'icon-tools',	
								region  : 'center',
								title 	: 'Configuracion',
								border	: false,
								items	: [confiCP]
							}]
						},{
							region  : 'center',
							layout  : 'fit',
							margins : '1 0 0 0',
							boder   : false,
							bodyStyle :'padding:0px;',
							items   : [{
								layout  : 'border',
								margins : '1 0 0 0',
								boder   : false,
								frame   : true,
								bodyStyle :'background-color:#eff4f8;',
								items   : [registros]
							}]
						}]
				},
				{
					title     : 'Mantenimiento',
					cls       : 'inner-tab-custom',
					//bodyStyle : 'padding:10px;',
					layout    : 'border',
					boder	  : false,
					hideMode  : Ext.isIE ? 'offsets' : 'display',
					listeners : {
						activate: function (tab){
							if(storeInfoMT.getCount() == 0){
								var maskViewMT = new Ext.LoadMask(tab.getEl());
								storeInfoMT.on("load",function(Store,records,options,groups){										
									var tplInfoMT = new Ext.XTemplate(
										'<div id="info-mt">',
											'<table class="tabla_resumen" cellspacing="6">',
												'<tbody>',
													'<tr>',
														'<td class="bold" >Placa    :</td>',
														'<td align="right">#{placa}</td>',
													'</tr>',
													'<tr>',
														'<td class="bold" >S Total [kva]:</td>',
														'<td align="right">{kvat}</td>',
													'</tr>',
													'<tr>',
														'<td ><b style="padding-left: 10px">[S1: </b>{kva1};<tpl if="0 != kva2"><b style="padding-left: 10px">S2: </b>{kva2};</tpl><tpl if="0 != kva3"><b style="padding-left: 10px">S3: </b>{kva3}</tpl><b>]</b></td>',
														'<td align="right"></td>',
													'</tr>',
													'<tr>',
														'<td class="bold" >Red de BT [mts] :</td>',
														'<td align="right">{metros_rs}</td>',
													'</tr>',
													'<tr>',
														'<td class="bold" >Factor de Carga :</td>',
														'<td align="right">{factor_cg}</td>',
													'</tr>',
													'<tr>',
														'<td class="bold" >Cant. Clientes :</td>',
														'<td align="right">{total_cli}</td>',
													'</tr>',
												'</tbody>',
											'</table>',
										'</div>'
									);							
									tplInfoMT.overwrite(Ext.get('mt-inf'), storeInfoMT.data.items[0].data);	
								});
								maskViewMT.show();	
								storeInfoMT.load({params:{idUT:idUT},
									callback: function(){
										maskViewMT.hide();
									}
								});
								//Ext.isIE ? maskView.hide() : null;	
							}
							if(storeKvaFase.getCount() == 0){
								storeKvaFase.on("load",function(Store,records,options,groups){			
									longCol = colKvaFase.length;
									for (i=0; i<longCol; i++){colKvaFase.shift();}
									
									Ext.each(storeKvaFase.fields.items,function(item){
										switch (item.name) { 
											case 'kva':
												colKvaFase.push({header:item.header,dataIndex:item.name,css:'background-color: #e2e6e7;'/*, width: 50*/});	
												break
											default: 
												colKvaFase.push({header:item.header,dataIndex:item.name/*, width: 50*/});
										} 
									});					
									var colModelKvaFase = new Ext.grid.ColumnModel({
										columns: colKvaFase, defaults: {sortable: false, menuDisabled: true}
									});			
									Ext.getCmp('KvaFaseMT').reconfigure(storeKvaFase,colModelKvaFase);	
								});
								storeKvaFase.load({params:{idUT:idUT}});
							}
							if(storeUsuKva.getCount() == 0){
								storeUsuKva.on("load",function(Store,records,options,groups){			
									longCol = colUsuKva.length;
									for (i=0; i<longCol; i++){colUsuKva.shift();}
									
									Ext.each(storeUsuKva.fields.items,function(item){
										switch (item.name) { 
											case 'fases':
												colUsuKva.push({header:item.header,dataIndex:item.name,css:'background-color: #e2e6e7;'/*, width: 50*/});	
												break
											default: 
												colUsuKva.push({header:item.header,dataIndex:item.name/*, width: 50*/});
										} 
									});					
									var colModelUsuKva = new Ext.grid.ColumnModel({
										columns: colUsuKva, defaults: {sortable: false, menuDisabled: true}
									});			
									Ext.getCmp('usuKvaMT').reconfigure(storeUsuKva,colModelUsuKva);	
								});
								storeUsuKva.load({params:{idUT:idUT}});
							}
							if(storeCorriMT.getCount() == 0){
								storeCorriMT.on("load",function(Store,records,options,groups){			
									longCol = colCorriMT.length;
									for (i=0; i<longCol; i++){colCorriMT.shift();}
									
									Ext.each(storeCorriMT.fields.items,function(item){
										switch (item.name) { 
											case 'info':
												colCorriMT.push({header:item.header,dataIndex:item.name,css:'background-color: #e2e6e7;'/*, width: 50*/});	
												break
											default: 
												colCorriMT.push({header:item.header,dataIndex:item.name/*, width: 50*/});
										} 
									});					
									var colModelCorriMT = new Ext.grid.ColumnModel({
										columns: colCorriMT, defaults: {sortable: false, menuDisabled: true}
									});			
									Ext.getCmp('CorriMT').reconfigure(storeCorriMT,colModelCorriMT);	
								});
								storeCorriMT.load({params:{root:root, numFase:numFase}});
							}
							if(storeStatusMT.getCount() == 0){
								storeStatusMT.on("load",function(Store,records,options,groups){			
									longCol = colStatusMT.length;
									for (i=0; i<longCol; i++){colStatusMT.shift();}
									
									Ext.each(storeStatusMT.fields.items,function(item){
										colStatusMT.push({header:item.header,dataIndex:item.name});
										/*switch (item.name) { 
											case 'info':
												colStatusMT.push({header:item.header,dataIndex:item.name,css:'background-color: #e2e6e7;'});	
												break
											default: 
												colStatusMT.push({header:item.header,dataIndex:item.name});
										} */
									});					
									var colModelStatusMT = new Ext.grid.ColumnModel({
										columns: colStatusMT, defaults: {sortable: false, menuDisabled: true}
									});			
									Ext.getCmp('StatusMT').reconfigure(storeStatusMT,colModelStatusMT);
								});
								storeStatusMT.load({params:{root:root, idUT:idUT, numFase:numFase}});
								Ext.Ajax.request({
										url : 'php/load/mantenimiento/chartTurnos.php' , 
										params : {root:root, idUT:idUT, numFase:numFase},
										method: 'POST',
										success: function ( result, request ) {
											var r_ChartMT = result.responseText;
											var d_ChartMT = new Array;
											d_ChartMT.push(	[r_ChartMT.split(';')[0].split(',')[0].trim(),parseFloat(r_ChartMT.split(';')[0].split(',')[1].trim())],
															[r_ChartMT.split(';')[1].split(',')[0].trim(),parseFloat(r_ChartMT.split(';')[1].split(',')[1].trim())],
															[r_ChartMT.split(';')[2].split(',')[0].trim(),parseFloat(r_ChartMT.split(';')[2].split(',')[1].trim())]);
											Ext.get('chartMT-details').remove();
											Ext.get('chartMT-padre').createChild({id:'chartMT-details', style: {'height':'100%', 'width':'100%'}});
											$().statusCarga('chartMT-details',d_ChartMT.sort(),parseFloat(r_ChartMT.split(';')[3].split(',')[1].trim()));
										},
										failure: function ( result, request)  {$().statusCarga('chartMT-details',[['N/A',0],['N/A',0],['N/A',0]],parseFloat(r_ChartMT.split(';')[3].split(',')[1].trim()));} 
								});
								Ext.getCmp('radioStatusMT').suspendEvents(true);
								Ext.getCmp('myrb').setValue(true);
								Ext.getCmp('radioStatusMT').resumeEvents();
								Ext.getCmp('cmb-T1').setValue('07:00');
								Ext.getCmp('cmb-T2').setValue('15:00');
								Ext.getCmp('cmb-T3').setValue('23:00');
								Ext.getCmp('cmb-H1').setValue('8 H');
								Ext.getCmp('cmb-H2').setValue('8 H');
								Ext.getCmp('cmb-H3').setValue('8 H');								
							}
							if(storeComboMT.getCount() == 0){
								storeComboMT.on('beforeload', function(store){
									store.baseParams = {numFase:numFase};
								});
								storeComboMT.load({params:{numEquip:numEquip}});
								Ext.getCmp('mycb').setValue('ST');
							}
							if(storeFactorMT.getCount() == 0){
								storeFactorMT.on("load",function(Store,records,options,groups){			
									longCol = colFactorMT.length;
									for (i=0; i<longCol; i++){colFactorMT.shift();}
									
									Ext.each(storeFactorMT.fields.items,function(item){
										switch (item.name) { 
											case 'fase':
												colFactorMT.push({header:item.header,dataIndex:item.name,css:'background-color: #e2e6e7;'/*, width: 50*/});	
												break
											default: 
												colFactorMT.push({header:item.header,dataIndex:item.name/*, width: 50*/});
										} 
									});					
									var colModelFactorMT = new Ext.grid.ColumnModel({
										columns: colFactorMT, defaults: {sortable: false, menuDisabled: true}
									});			
									Ext.getCmp('FactorMT').reconfigure(storeFactorMT,colModelFactorMT);	
								});
								storeFactorMT.load({params:{root:root, idUT:idUT, numFase:numFase, numEquip:numEquip}});
							}
						}
					},
					items    : [{
							region    : 'north',
							layout    : 'border',
							border    : false,
							height    : 270,
							minSize   : 100,
							maxSize   : 350,
							cmargins  : '5 5 5 5',
 							bodyStyle :'padding:0px;',
							split     : true,
							items	  : [{
								region    : 'west',
								//title     : 'Resumen nominal',
								width	  : '50%',
								layout    : 'border',
								margins   : '1 0 1 1',
								bodyStyle : 'padding:0px;',
								border    : true,
								items     : [{
									region    : 'west',
									width	  : '55%',
									layout    : 'border',
									margins   : '0 0 0 0',
									bodyStyle : 'padding:0px;',
									border    : false,
									items	  : [{
										region    : 'center',
										margins   : '0 0 0 0',
										bodyStyle : 'padding:0px;',										
										frame:true,
										split:true,
										boder 	  : false,	
										html	  : '<div id="mt-inf" style="/*font-size:14px;*/"></div>'
									},{
										region : 'south',
										border : false,
										margins   : '0 0 0 0',
										height    : 100,
										items  : [tablaKavFase]
									}]
								},{
									region    : 'center',
									//layout    : 'fit',
									margins   : '0 0 0 0',
									bodyStyle : 'padding:0px;',
									//boder 	  : false,
									items     : [tablaUsuKav]
								}]
							},{
								region      : 'center',
								margins   : '0 0 0 5',
								layout      : 'border',
								border		: false,
								items       : [{
									region    : 'north',
									//layout    : 'fit',
									height	  : 125,
									bodyStyle : 'padding:0px;',
									boder 	  : true,
									items     : [tablaCorriMT]
								},{
									region     : 'center',
									//autoHeight : true,
									bodyStyle  : 'padding-bottom:0px;',
									autoScroll : true,
									items      : [tablaFactorMT]
								}]
							}]
						},{
							region      : 'center',
							margins     : '0 0 0 0',
							layout      : 'border',
							border		: false,
							items       : [{
								region      : 'center',
								title     	: 'Condici&oacute;n de carga para Unidades y Equipos de Transformaci&oacute;n',
								margins     : '0 0 0 0',
								layout      : 'border',
								border      : true,
								items       : [{
									region    : 'west',
									layout    : 'fit',
									boder 	  : false,
									width	  : '33%',
									margins   : '0 0 0 0',
									bodyStyle : 'padding:0px;',
									items     : [gridStatusMT]
								},{
									region      : 'center',
									margins     : '5 10 10 10',
									layout      : 'border',
									border		: false,
									items       : [{
										region      : 'north',
										//xtype		: 'fieldset',
										//title		: 'Individual Radios',
										autoHeight		: true,
										defaultType	: 'radio', // each item will be a radio button
										items: [{
											id         : 'radioStatusMT',
											xtype      : 'radiogroup',
											cls    : 'x-check-group-alt',
											fieldLabel : 'Filtros de la tabla',
											allowBlank : false,
											items      : [{
												columnWidth: '.40',
												items: [
													{id: 'myrb', boxLabel: 'Sobrecargado', name: 'rb-stats', inputValue: 1, checked: true},
													{boxLabel: 'Normal', name: 'rb-stats', inputValue: 2},
													{boxLabel: 'Subutilizado', name: 'rb-stats', inputValue: 3}
												]
											},{
												columnWidth: '.40',
												items: [
													{xtype: 'label', text: 'Unidad T', cls:'x-form-check-group-label', anchor:'-15'},
													{xtype: 'label', cls:'x-menu-sep', style:{'background-color':'#D1DCEE', 'border-bottom-color':'#D1DCEE'}},
													comboMT
												]
											},{
												columnWidth: '.20'
											}],
											listeners: {
												change: function (radio){
													switch (Ext.getCmp('radioStatusMT').getValue().getGroupValue()) { 
														case '1': statsUE = 'SC'; break
														case '2': statsUE = 'RG'; break
														case '3': statsUE = 'SU'; break
														default:  statsUE = 'SC'; 
													} 
													storeStatusMT.load({params:{root:root, idUT:idUT, numFase:numFase, reg_s:reg_s, statsUE:statsUE}});
													Ext.Ajax.request({
														url : 'php/load/mantenimiento/chartTurnos.php' , 
														params : {
															root:root, 
															idUT:idUT, 
															numFase:numFase, 
															reg_s:reg_s, 
															statsUE:statsUE, 
															h1:h1,
															h2:h2,
															h3:h3
														},
														method: 'POST',
														success: function ( result, request ) {
															var r_ChartMT = result.responseText;
															var d_ChartMT = new Array();
															d_ChartMT.push(	[r_ChartMT.split(';')[0].split(',')[0].trim(),parseFloat(r_ChartMT.split(';')[0].split(',')[1].trim())],
																			[r_ChartMT.split(';')[1].split(',')[0].trim(),parseFloat(r_ChartMT.split(';')[1].split(',')[1].trim())],
																			[r_ChartMT.split(';')[2].split(',')[0].trim(),parseFloat(r_ChartMT.split(';')[2].split(',')[1].trim())]);
															Ext.get('chartMT-details').remove();
															Ext.get('chartMT-padre').createChild({id:'chartMT-details', style: {'height':'100%', 'width':'100%'}});
															$().statusCarga('chartMT-details',d_ChartMT.sort(),parseFloat(r_ChartMT.split(';')[3].split(',')[1].trim()));
														},
														failure: function ( result, request)  {$().updateStCa([['N/A',0],['N/A',0],['N/A',0]],parseFloat(r_ChartMT.split(';')[3].split(',')[1].trim()));} 
													});
												}
											}
										}]
									},{
										region      : 'center',
										margins     : '10 0 0 0',
										frame		: true,
										bodyStyle 	: 'background-color:#eff4f8;',
										layout      : 'border',
										border		: false,
										items       : [{
											id		   : 'chartMT',/*'+Ext.getCmp('chartMT').getInnerHeight()+'*/
											region	   : 'center',/*'+Ext.getCmp('chartMT').getInnerWidth()+'*/
											boder      : false,
											bodyStyle  : 'padding-bottom:0px;', 
											autoScroll : true,
											html	   : '<div id="chartMT-padre" style="width:100%; height:100%;"><div id="chartMT-details" style="width:100%; height:100%;"></div></div>'
										},confiTurn]
										
									}]		
								}]
							}]
							
						}]
				},
				{
					title     : 'Operacion',
					disabled  :true,
					bodyStyle : 'padding:10px;',
					html      : 'Modulo en Construccion.'
				},
				{
					title     : 'Comenrcializacion',
						disabled:true,
					bodyStyle : 'padding:10px;',
					html      : 'Modulo en Construccion.'
				}/*,
				{
					title     : 'Planificacion',
					disabled  :true,
					bodyStyle : 'padding:10px;',
					html      : 'Modulo en Construccion.'
				}*/
			]
};