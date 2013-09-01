/*!
 * Ext JS Library 3.3.0
 * Copyright(c) 2006-2010 Ext JS, Inc.
 * licensing@extjs.com
 * http://www.extjs.com/license
 */

//
// This is the main layout definition.
//


Ext.onReady(function(){
	//Ext.BLANK_IMAGE_URL = '../ext/resources/images/default/s.gif';
	
    Ext.QuickTips.init(true);

//	Ext.QuickTips.init();
	
	var detailEl;	
	
	var contentPanel = new Ext.Panel({
		id: 'content-panel',
		region: 'center', // this is what makes this panel into a region within the containing layout
		layout: 'card',	
		margins:'35 5 5 0',
		cmargins:'35 5 5 5',
		activeItem: 0,
		border: false,
		//xtype:	"panel",
		items:[start, absolute]//<--- dentro de la región central normalmente va el contenido principal, así que poner ahi los cards tiene mucho sentido.
	});
	
	var winUpload = new Ext.Window({
		id: 'win-medicion',
		title: 'Cargar archivo al Servidor',
		resizable : false,
		modal : true,
		width: 450,
		autoDestroy : true,
		maskDisabled: true,
		closeAction : 'hide',
		items : [txtFile]
	});
	
	var winCampana = new Ext.Window({
		id: 'win-campana',
		title: 'Cargar archivo al Servidor',
		resizable : false,
		modal : true,
		width: 450,
		autoDestroy : true,
		maskDisabled: true,
		closeAction : 'hide',
		items : [csvFile]
	});
	
	var treePanel = new Ext.tree.TreePanel({  
    	id: 'tree-panel',	
        //region:'center', 
    	title: 'SGRD [Mediciones]',
		iconCls		: 'icon-muestra',	
        minSize: 150,
        rootVisible: false,
		border: false,
        lines: false,
        //singleExpand: true,
        //useArrows: true,
		autoScroll:true,
		dataUrl:'php/tree/tree-order.php',
		enableDD: false,
		root: new Ext.tree.AsyncTreeNode({
			text: 'root',
			draggable: false			
		}),
		tbar: {
			items: [  
				{
					//text: 'add',
					id       : 'add-cvs',
					disabled : true,
					iconCls  : 'icon-campana-add', 
					tooltip	 : 'Agregar Campa&#241;a',
					handler  : function(){ 
						winCampana.show();
					}
				},
				'-',
				{
					//text: 'Agregar',
					id       : 'add-txt',
					disabled : true,
					iconCls  : 'icon-muestra-add', 
					tooltip	 : 'Agregar Medici&oacute;n',
					handler  : function(){ 
						winUpload.show();  
					}
				},
				'->', 
				{
					id		: 'expand_tree',
					iconCls : 'icon-expand-all', 
					tooltip	 : 'Expandir todo',
					handler : function(){ 
						treePanel.expandAll();
					}
				}, 
				'-',
				{
					id		: 'collapse_tree',
					iconCls: 'icon-collapse-all', 
					tooltip	 : 'Contraer todo',
					handler: function(){ 
						treePanel.collapseAll();						
					}
				}
			] 
		}
	});
	
	// This is the Details panel that contains the description for each example layout.
	var loginPanel = {
		id			: 'login-panel',
        title       : 'Gesti&oacute;n de Usuario',
		iconCls		: 'icon-user',
		collapsible : true,
        //region		: 'south',
        height		: 200,
		split		: true,
		border      : false,
        bodyStyle	: 'padding-bottom:15px;background:#eee;',
		autoScroll	: true,
		items		:[],
		tbar: {
			items: [  
				{
					//text: 'add',
					iconCls: 'icon-user-go', 
					tooltip	 : 'Login Usuario',
					handler: function(){ 
						winLogin.show();
					}
				},
				'-',
				{
					//text: 'add',
					iconCls: 'icon-user-add', 
					tooltip	 : 'Crear Usuario',
					handler: function(){ 
						//winLogin.show();
					}
				},
				'-'
			] 
		}
    };
	
	var storePlacaMT = new Ext.data.JsonStore({
		url : 'php/load/autoComboBox.php'
    });

    // Custom rendering Template
    var resultTpl = new Ext.XTemplate(
        '<tpl for="."><div class="search-item" style="width:400;">',
            /*'<h3><span>{placa}<br />by {fecha}</span></h3>',*/
			'<h4 style="padding:5px;">Placa: {placa} - {fecha}</h4>',
        '</div></tpl>'
    );
	
	var search = new Ext.form.ComboBox({
        store		 : storePlacaMT,
        displayField : 'title',
        typeAhead	 : false,
        loadingText	 : 'Buscando...',
		fieldLabel	 : 'Buscar. (min 4 caracteres)',
		emptyText	 : 'Placa o CC',
		listWidth 	 : 240,
        width		 : 185,
        pageSize	 : 10,
        hideTrigger	 : true,
        tpl			 : resultTpl,
        itemSelector : 'div.search-item',
        onSelect: function(record){ // override default onSelect to do redirect
			loadData(record.data.root);
        }
    });
	
	var mtPanel = {
		id			: 'mt-panel',
        title       : 'Mantenimiento',
		iconCls		: 'icon-mto',
		collapsible : true,
        height		: 200,
		split		: true,
		border      : false,
        bodyStyle	: 'padding-bottom:15px;background:#eee;',
		autoScroll	: true,
		items		:[{
			xtype	   : 'form',
			boder 	   : false,
			frame	   : true,
			labelAlign : 'top',
			margins    : '0 0 0 0',
			bodyStyle  : 'padding:0px;',
			items      : [search]
		}],
		tbar: {
			items: [  
				{
					text:'Unidades-SC',
					align:'center',
					iconCls: 'icon-ut', 
					tooltip	 : 'Unidades Sobrecargadas',
					handler: function(){ 
						//winLogin.show();
					}
				},
				'-',
				{
					text:'Equipos-SC',
					align:'center',
					iconCls: 'icon-eq', 
					tooltip	 : 'Equipos Sobrecargados',
					handler: function(){ 
						winMtoEquipo.show();
					}
				}
			] 
		}
    };
	
	var winLogin = new Ext.Window({
		id     : 'win-login',
		title:'Validaci&oacute;n de Usuario',
		layout:'fit',
		bodyStyle:'padding:10px 5px 5px 5px;',
		width:340,
		height:200,
		resizable:false,
		modal:true,
		autoScroll: true,
		maximizable:false,
		closable:false,
		plain: true,
		buttonAlign:'center',
		items:[loginForm],
		buttons: [{
			text:'Aceptar',
			align:'center',
			handler: function (){
				Validar();
				//Ext.getCmp('add-cvs').enable();
				//Ext.getCmp('add-txt').enable();
			},
		},{
			text:'Cerrar',
			align:'center',
			handler: function (){
				winLogin.hide();
			}
		}]
	});
	
	function loadData(id_muestra){
		
		Ext.getCmp('tab-container').setActiveTab(0);
			
		storeInfoCP.removeAll();
		storeRegCP.removeAll();
		storeMinMaxCP.removeAll();
		storeGridCP.removeAll();
		
		storeUsuKva.removeAll();
		storeKvaFase.removeAll();
		storeInfoMT.removeAll();
		storeCorriMT.removeAll();
		storeStatusMT.removeAll();
		storeComboMT.removeAll();
		storeFactorMT.removeAll();
		
		Ext.getCmp('content-panel').layout.setActiveItem('tab-container');				
		var maskView = new Ext.LoadMask(Ext.getCmp('panelTpl').getEl());
		//Ext.getCmp('absolute-panel').getEl().mask('Cargando....');
		root = id_muestra;
		/*console.debug(root);*/
		Ext.Ajax.request({
			url : 'php/db_sgd/getDataUtran.php' , 
			params : {root:root},
			method: 'POST',
			success: function ( result, request ) { 
				var resultado = result.responseText;
				idUT = resultado.split(';')[0].trim();
				numFase = parseInt(resultado.split(';')[1].trim());
				numEquip = parseInt(resultado.split(';')[2].trim());
				
				menuFase.baseParams = {numFase: numFase};
				menuFase.loaded=false;
				menuTipo.baseParams = {numFase: numFase};
				menuTipo.loaded=false;		
				menuExportar.baseParams = {numFase: numFase};
				menuExportar.loaded=false;				
				
				storeData.on("load",function(Store,records,options,groups){
					longCol = colGrid.length;
					for (i=0; i<longCol; i++){colGrid.shift();}
					
					Ext.each(storeData.fields.items,function(item){
						switch (item.name) { 
							case 'V1':
							case 'V2':
							case 'V3':
							case 'Vp':
								colGrid.push({
									header:item.header,
									dataIndex:item.name,
									renderer: function(value, metaData, record, rowIndex, colIndex, store){
										return value!=null?value <= 120*0.1?'<p style="color:#f00">'+value+'</p>':'<p style="color:#000">'+value+'</p>':null;
									}
								});   	
								break 
							case 'date':
								colGrid.push({header:item.header,dataIndex:item.name,width:140}); break 
							case 'Desb':
								colGrid.push({
									header:item.header,
									dataIndex:item.name,
									renderer: function(value, metaData, record, rowIndex, colIndex, store){
										return value==0?null:value;
									}
								});
								break
							default: 
								colGrid.push({header:item.header,dataIndex:item.name});
						} 
					});					
					var colModel = new Ext.grid.ColumnModel({
						columns: colGrid
						//defaults: {sortable: true, menuDisabled: true,	width: 100},
					});			
					Ext.getCmp('gridData').reconfigure(storeData,colModel);				
				});	
				storeData.load({params:{start:0, limit:25, root:root, numFase:numFase}});
				
				storeResum.on("load",function(Store,records,options,groups){
					longCol = colResum.length;
					for (i=0; i<longCol; i++){colResum.shift();}
					
					Ext.each(storeResum.fields.items,function(item){
						switch (item.name) { 
							case 'V1':
							case 'V2':
							case 'V3':
								colResum.push({
									header:item.header,
									dataIndex:item.name,
									renderer: function(value, metaData, record, rowIndex, colIndex, store){
										return value!=null?value <= 120*0.1?'<p style="color:#f00">'+value+'</p>':'<p style="color:#000">'+value+'</p>':null;
									}
								});   	
								break 
							case 'DATA':
								colResum.push({header:item.header,dataIndex:item.name,css:'background-color: #e2e6e7;',menuDisabled: true, width: 70});	
								break
							default: 
								colResum.push({header:item.header,dataIndex:item.name});
						} 
					});					
					var colModelResum = new Ext.grid.ColumnModel(colResum);			
					Ext.getCmp('gridResum').reconfigure(storeResum,colModelResum);				
				});	
				storeResum.load({params:{root:root, numFase:numFase}});
				
				storeHeader.on("load",function(Store,records,options,groups){
					panelView.doLayout();
				});
				maskView.show();			
				storeHeader.load({params:{root:root},
					callback: function(){
						maskView.hide();	
					}
				});
				Ext.isIE ? maskView.hide() : null;
			},
			failure: function ( result, request) { 
				Ext.MessageBox.alert('Failed', result.responseText); 
			} 
		});		
		
		//$.download('php/download/downloadTXT.php','filename=mySpreadsheet&format=xls');
		
		/*Ext.Ajax.request({
			url : 'php/download/downloadTXT.php' , 
			//params : {root:root},
			//method: 'POST',
			success: function ( result, request ) { },
			failure: function ( result, request) { } 
		});*/
	
	};
       
	// Assign the changeLayout function to be called on tree node click.
	treePanel.on('click',function(node){  //Ext.Msg.alert('Status', 'Changes saved successfully.');
		if((node.leaf)&&(root != node.attributes["id-tabla"])&&(node.attributes["idParent"]!=null)){ //si no es una carpeta entramos	
			loadData(node.attributes["id-tabla"]);
		}
	},this);
	
	// Finally, build the main layout once all the pieces are ready.  This is also a good
	// example of putting together a full-screen BorderLayout within a Viewport.  beforeshow
		
	var mainViewport = new Ext.Viewport({					 
		id : 'mainViewport',
		layout: 'border',
		title: 'Ext Layout Browser',
		items: [{
			region	: 'west',
			id		: 'west-panel',
			title	: 'Menu',
			//iconCls	: 'icon-menu',	
			split	: true,
			width	: 200,
			minSize	: 175,
			maxSize	: 400,
			//collapsible: true,
			margins	: '35 0 5 5',
			cmargins: '35 5 5 5',
			layout	: 'accordion',
			layoutConfig:{
			 animate:true
			},
			items: [treePanel, mtPanel, loginPanel]
		},
		contentPanel],
        renderTo: Ext.getBody()
    });
});



