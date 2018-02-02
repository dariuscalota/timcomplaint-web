var initMap = function () {


	$.get("http://localhost/www/timcomplaint-api/complaint/read.php", function (data, status) {
		console.log(data, status);
		var locations = [];

		data.records.forEach(function (rec, idx) {
			var _loc = rec.location;
			var _latlng = _loc.split(",");
			var locObj = {
				lat: _latlng[0],
				lng: _latlng[1],
				type: rec.category_name,
				status: rec.status,
				description: rec.description,
				created: rec.created
			}
			locations.push(locObj);
		});

		var map = new google.maps.Map(document.getElementById('map'), {
			zoom: 13,
			center: new google.maps.LatLng(45.759780, 21.230020),
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});

		var infowindow = new google.maps.InfoWindow();

		var marker;

		locations.forEach(function (rec, idx) {
			var infoWinContent="";

			infoWinContent = infoWinContent.concat('<b>',rec.type,'</b>','<br>');
			infoWinContent = infoWinContent.concat('<b>',rec.status,'</b>','<br>');
			infoWinContent = infoWinContent.concat('<div style="max-width:200px;">',rec.description,'</div>','<br>');
			infoWinContent = infoWinContent.concat('<i>',rec.created,'</i>');

			marker = new google.maps.Marker({
				position: new google.maps.LatLng(rec.lat, rec.lng),
				icon: './assets/pictures/pin.png',
				map: map
			});

			google.maps.event.addListener(marker, 'click', (function (marker, idx) {
				return function () {
					infowindow.setContent(infoWinContent);
					infowindow.open(map, marker);
				}
			})(marker, idx));

		});
	});

};