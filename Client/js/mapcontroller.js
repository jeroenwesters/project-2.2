var map = null;

function createMap(divid){
  map = L.map(divid).setView([64,11], 4);


  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(map);

  L.marker([64,11]).addTo(map)
      .bindPopup('Example marker:<br>Station: 0000 <br> Snowfall: SNDP 0')
      .openPopup();

  L.marker([60,15]).addTo(map)
      .bindPopup('Example marker:<br>Station: 0000 <br> Snowfall: SNDP 0');
}


function createMarker(lon, lat, desc, id){
  L.marker([lon,lat]).addTo(map)
  .bindPopup('Example marker:<br>Station: '+ id + ' <br> Snowfall: SNDP 0');
}

document.onkeyup = function(e) {
  if (e.which == 32) {
      console.log("asfasf");
      createMarker(60, 17, 'test', 5125);
  }
};
