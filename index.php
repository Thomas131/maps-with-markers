<?php
	 /**
	  * @TODO: Be able to disable categories of markers
	  * @var bool MAP-SCRIPT To block direct access to config.php
	  */
	define('MAP-SCRIPT',1);
	require("config.php");
?>
<!-- V1.3 -->
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<style type="text/css">
			html,body,#map-canvas {
				height: 100%;
				width:100%;
				margin: 0;
				padding: 0;
			}

			#content h1 {
				font-size: 150%;
			}

			<?php if($IS_ADMIN) { ?>
				#my_controlls b {
					font-size: 16px;
				}
			<?php } ?>
		</style>
		<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $APT_KEY; ?>&libraries=places&language=de&v=3.exp"></script>
		<script type="text/javascript">
			/**
			 * @var array markerdata The data of the markers of the map. Added via PHP.
			 */

			var markerdata = <?php echo file_get_contents("data.json"); ?>;

			/**
			 * @var array types Array of types of Markers, changeable in config.php
			 */
			var types = <?php echo $CATEGORIES; ?>;

			<?php if($IS_ADMIN) { ?>
				var reload = true; 	// @TODO: you should be able to edit the map, without reloading everything
			<?php } ?>
			
			<?php if($IS_ADMIN) { ?>
				/**
				 * My Functions
				 */		
				function save(id) {
					markerdata[id].name = document.querySelector("#content h1").innerHTML;
					markerdata[id].mail = document.querySelector("#content input#mail").value;
					var type_sel = document.querySelector("#content select#type");
					markerdata[id].type = parseInt(type_sel.options[type_sel.selectedIndex].value);

					save_to_server();
				}

				function save_to_server() {
					$.ajax("save.php",
						{
							type: "POST",
							async: false,
							data: { data: JSON.stringify(markerdata) },
							success: function() {
								alert("Erfolgreich gespeichert!");
								if(reload)
									window.location.reload();
							}
						}
					);
				}
				
				function add() {
					markerdata.push(
						{
							name: "Name",
							locx: map.getCenter().lng(),
							locy: map.getCenter().lat(),
							mail: "e@mail.adresse",
							type: 0
						}
					);
					create_marker(markerdata.length-1);

					
				}
			<?php } ?>

			/**
			 * Outputs the template for the Infowindow
			 * @param int id which index of markerdata
			 * @return string HTML-String
			 */
			function infowindow_tpl(id) {
				<?php if($IS_ADMIN) { ?>
					var returnvar = '<div id="content">' +
								'<h1 contenteditable="true">' + markerdata[id].name + '</h1>' +
								'<input value="' + markerdata[id].mail + '" id="mail"><br />' +
								'<select id="type">';
								
					for(var i=0; i < types.length; i++) {
						returnvar += '<option' + ((markerdata[id].type == (i % types.length)) ? " selected" : "") + ' value="' + ( i % types.length ) + '">' + types[( i % types.length )] + '</option>';
					}
					
					returnvar += '</select>' +
								'<input type="button" value="Löschen" onclick="markerdata.splice(' + id + ',1);save_to_server();">' +
								'<input type="button" value="Speichern" onclick="save(' + id + ');">' +
							'</div>';
					return returnvar;
				<?php } else { ?>
					return '<div id="content"><h1>' + markerdata[id].name + '</h1><a href="mailto:' + markerdata[id].mail + '">E-Mail-Kontakt</a></div>';
				<?php } ?>
			}

			/**
			 * creates Markers and infowindows
			 * @param int id which index of markerdata
			 */
			function create_marker(id) {
				var marker = new google.maps.Marker({
					position: new google.maps.LatLng(markerdata[id].locy, markerdata[id].locx),
					map: map,
					title: markerdata[id].name,
					<?php if($IS_ADMIN) { ?>draggable:true,<?php } ?>
					animation: google.maps.Animation.DROP,
					icon: "images/" + markerdata[id].type + ".png"
				});

				//open/close infowindow; Thanks to http://stackoverflow.com/questions/25012029/google-maps-api-js-v3-infowindow-getposition-undefined
				google.maps.event.addListener(marker, 'click', function() {
					infowindow.unbind('position');
					if(infowindow.getPosition() != this.getPosition()) {	//open
						infowindow.setContent(infowindow_tpl(id));
						
						infowindow.bindTo('position',this,'position');
						infowindow.open(map,this);
					} else {           										//close
						infowindow.close();
						infowindow.setPosition(null);
					}
				});

				<?php if($IS_ADMIN) { ?>
					google.maps.event.addListener(marker, 'dragend', function() {
						markerdata[id].locx = marker.getPosition().lng();
						markerdata[id].locy = marker.getPosition().lat();
					});
				<?php } ?>
			}

			/**
			 * Initialize everything
			 */
			
			$(document).ready(function() {
				/**
				 * Init Infowindow
				 */
				infowindow = new google.maps.InfoWindow({  });
				
				map = new google.maps.Map(document.getElementById("map-canvas"), {
					center: new google.maps.LatLng(<?php echo $CENTER_Y; ?>, <?php echo $CENTER_X; ?>),
					zoom: <?php echo $DEFAULT_ZOOM; ?>
				});

				/**
				 * Search Box & Buttons
				 */
				
				map.controls[google.maps.ControlPosition.RIGHT_TOP].push(document.getElementById('my_controlls'));
				
				var input = (document.getElementById('pac-input'));
				map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

				var searchBox = new google.maps.places.SearchBox(input);

				google.maps.event.addListener(searchBox, 'places_changed', function() {
					var places = searchBox.getPlaces();

					if (places.length != 1) {
						return;
					}

					map.setZoom(<?php echo $DEFAULT_ZOOM_ON_SEARCH; ?>);
					map.setCenter(places[0].geometry.location);
				});

				/**
				 * Place Markers
				 */
				for(i = 0; i < markerdata.length; i++) {
					setTimeout("create_marker(" + i + ");", i * 200);
				}
			});
		</script>
	</head>
	<body>
		<div style="height:100%;width:100%;">
			<noscript><div style="height:100%;width:100%;"><h1>Für die Karte brauchst du leider Javascript aktiviert!</h1></div></noscript>
			
			<input id="pac-input" type="text" placeholder="Suchbox" size="50">
			<div id="map-canvas"></div>
			<?php if($IS_ADMIN) { ?>
				<div id="my_controlls">
					<span id="add" onclick="add();" style="padding: 4px; background-color: green; position: relative; border-radius: 5px;"><b>&nbsp;+&nbsp;</b></span>
					<span id="save" onclick="save_to_server();" style="padding: 4px; background-color: blue; position: relative; border-radius: 5px; color: yellow;"><b>Save</b></span>
				</div>
			<?php } ?>
		</div>
	</body>
</html>