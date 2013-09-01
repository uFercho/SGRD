var chart;

$(document).ready(function() {
						   
	jQuery.download = function(url, data, method){
		//url and data options required
		if( url && data ){ 
			//data can be string of parameters or array/object
			data = typeof data == 'string' ? data : jQuery.param(data);
			//split params into form inputs
			var inputs = '';
			jQuery.each(data.split('&'), function(){ 
				var pair = this.split('=');
				inputs+='<input type="hidden" name="'+ pair[0] +'" value="'+ pair[1] +'" />'; 
			});
			//send request
			jQuery('<form action="'+ url +'" method="'+ (method||'post') +'">'+inputs+'</form>')
			.appendTo('body').submit().remove();
		};
	};

	jQuery.fn.limitVolt = function(idEl,volProm,iniDia,finDia,limSup,limInf) {
		
		$.jqplot.config.enablePlugins = true;
		   
		Top = [[iniDia,limSup],[finDia,limSup]];
		Btm = [[iniDia,limInf],[finDia,limInf]];


		chartCP = $.jqplot(idEl,[volProm,Top,Btm],{
			title: 'Limite de Voltage por Calidad de Producto',
			seriesDefaults:{
				lineWidth: 1,
				neighborThreshold:0, 
				showMarker:false,
				pointLabels: {
					show : false,
				}
			}, 
			axes: {
				xaxis: {
					renderer: $.jqplot.DateAxisRenderer,
					tickOptions: {
					   formatString: '%a %d/%m/%y %H:%M:%S'
					},
					numberTicks: 6
				},
				yaxis: {
					tickOptions: {
						formatString: '%.3f [V]'
					}
				}
			},
			legend: {show:true},
			series:[
					{label:'Vol prom', breakOnNull: true},{label:'Lim Superior', color: '#F00;'},{label:'Lim Inferior', color: '#F00;'}
			],
			axesDefaults:{useSeriesColor: true},
			highlighter: {show:true, bringSeriesToFront:true},
			cursor: {
				//show: true,
				zoom: true
			}
		});
	};	
	
	jQuery.fn.grupoFase = function(idEl,cV,cI,cS,cPf,cFase) {
		
		$.jqplot.config.enablePlugins = true;
		
		var cTitle = 'Grafica de parametros';
		switch (cFase) { 
			case 'f1':  cTitle=cTitle+' [F1]'; break
			case 'f2':  cTitle=cTitle+' [F2]'; break
			case 'f3':  cTitle=cTitle+' [F3]'; break
		} 
		
		data   = new Array();
		series = new Array();
		ejesY = new Array('yaxis','y2axis','y3axis','y4axis');
		
		if(cV!=null){  
			data.push(cV);  
			series.push({label:'Voltaje   [V]' , breakOnNull: true, yaxis:ejesY.shift()}); 
		}
		if(cI!=null){  
			data.push(cI);  
			series.push({label:'Corriente [A]' , breakOnNull: true, yaxis:ejesY.shift()}); 
		}
		if(cS!=null){  
			data.push(cS);  
			series.push({label:'Potencia  [VA]', breakOnNull: true, yaxis:ejesY.shift()}); 
		}
		if(cPf!=null){ 
			data.push(cPf); 
			series.push({label:'FPotencia [%]' , breakOnNull: true, yaxis:ejesY.shift()}); 
		}
		
		chartFase = $.jqplot(idEl,data,{
			title: cTitle,
			seriesDefaults:{
				lineWidth: 1,
				neighborThreshold:0, 
				showMarker:false,
				pointLabels: {
					show : false,
				}
			}, 
			axes: {
				xaxis: {
					renderer: $.jqplot.DateAxisRenderer,
					tickOptions: {
					   formatString: '%a %d/%m/%y %H:%M:%S'
					},
					numberTicks: 6
				},
				yaxis: {
					tickOptions: {
						formatString: '%.3f'
					}
				}
			},
			legend: {show:true},
			series: series,
			axesDefaults :{useSeriesColor: true},
			highlighter: {show:true, bringSeriesToFront:true},
			cursor: {
				//show: true,
				zoom: true
			}
		});
	};
	
	jQuery.fn.grupoTipo = function(idEl,f1,f2,f3,cTipo) {
		
		$.jqplot.config.enablePlugins = true;
		
		var cTitle = 'Grafica por Fases';
		var cUnidad = '';
		switch (cTipo) { 
			case 'V':  cTitle='Voltaje por Fases'; cUnidad='V'; break
			case 'I':  cTitle='Corriente por Fases'; cUnidad='A'; break
			case 'S':  cTitle='Potencia por Fases'; cUnidad='VA'; break
			case 'PF': cTitle='Factor de Potencia por Fases'; cUnidad='%'; break
		} 
		
		data   = new Array();
		series = new Array();
		
		
		if(f1!=null){  
			data.push(f1);  
			series.push({label:''+cTipo+'1 ['+cUnidad+']', breakOnNull: true}); 
		}
		if(f2!=null){  
			data.push(f2);  
			series.push({label:''+cTipo+'2 ['+cUnidad+']', breakOnNull: true}); 
		}
		if(f3!=null){  
			data.push(f3);  
			series.push({label:''+cTipo+'3 ['+cUnidad+']', breakOnNull: true}); 
		}

		chartTipo = $.jqplot(idEl,data,{
			title: cTitle,
			seriesDefaults:{
				lineWidth: 1,
				neighborThreshold:0, 
				showMarker:false,
				pointLabels: {
					show : false,
				}
			}, 
			axes: {
				xaxis: {
					renderer: $.jqplot.DateAxisRenderer,
					tickOptions: {
					   formatString: '%a %d/%m/%y %H:%M:%S'
					},
					numberTicks: 6
				},
				yaxis: {
					tickOptions: {
						formatString: '%.3f ['+cUnidad+']'
					}
				}
			},
			legend: {show:true},
			series:series,
			axesDefaults:{useSeriesColor: true},
			highlighter: {show:true, bringSeriesToFront:true},
			cursor: {
				//show: true,
				zoom: true
			}
		});
	};
	
	jQuery.fn.miscelaneos = function(idEl,data,Otros) {
		
		$.jqplot.config.enablePlugins = true;
		
		var cTitle = 'Grafica';
		var cUnidad = '';
		switch (Otros) { 
			case 'Vp':  cTitle='Voltaje promedio'; cUnidad='V'; break
			case 'In':  cTitle='Corriente del neutro'; cUnidad='A'; break
			case 'Db': cTitle='Grafica de Desbalance'; cUnidad='%'; break
			case 'St':  cTitle='Potencia total'; cUnidad='VA'; break
			case 'PFt': cTitle='Factor de Potencia total'; cUnidad='%'; break
			case 'Wt': cTitle='Grafica de Energia'; cUnidad='Wh'; break
		} 

		chartOtros = $.jqplot(idEl,[data],{
			title: cTitle,
			seriesDefaults:{
				lineWidth: 1,
				neighborThreshold:0, 
				showMarker:false,
				pointLabels: {
					show : false,
				}
			}, 
			axes: {
				xaxis: {
					renderer: $.jqplot.DateAxisRenderer,
					tickOptions: {
					   formatString: '%a %d/%m/%y %H:%M:%S'
					},
					numberTicks: 6
				},
				yaxis: {
					tickOptions: {
						formatString: '%.3f ['+cUnidad+']'
					}
				}
			},
			legend: {show:true},
			series:[
					{label: Otros+' ['+cUnidad+']', breakOnNull: true}
			],
			axesDefaults:{useSeriesColor: true},
			highlighter: {show:true, bringSeriesToFront:true},
			cursor: {
				//show: true,
				zoom: true
			}
		});
	};
	
	jQuery.fn.updateStCa = function updateSeries(data) {
			plot2b.series[0].data = data;
			plot2b.drawSeries({}, 0);
			plot2b.axes.max = 500;
            plot2b.replot;
			plot2b.resetAxesScale;
			return false;
	};
	
	jQuery.fn.statusCarga = function(idEl,data,total) {
		
		$.jqplot.config.enablePlugins = true;
		
		plot2b = $.jqplot(idEl, [data], {
			//captureRightClick: true,
			title: 'Estado de Carga de los Tranformadores [Total horas: '+total+']',
			seriesDefaults:{
				renderer:$.jqplot.BarRenderer,
				rendererOptions:{
					 barWidth:55, 
					 barPadding:-25, 
					 barMargin:25, 
					 varyBarColor: true
				},
				pointLabels: {
					location: 's'
				},
				showMarker:false,
				breakOnNull: true
			},
			axes: {
				xaxis: {
					renderer: $.jqplot.CategoryAxisRenderer
				},
				yaxis: {
					//max: 10,
					min: 0,
					tickOptions: {
					   formatString: '%.3f'
					}
				}
			}
		});
	};
});