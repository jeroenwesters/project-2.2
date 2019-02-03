var map = null;
var apikey = "";

function createMap(divid, key){
  map = L.map(divid).setView([64,11], 4);
  apikey = key;

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(map);

  getStations();

}


function getStations(){
  var countries = ["denmark", "norway", "sweden"];

  for (i = 0; i < countries.length; i++) {
      get_data(countries[i], handleCountryStations);
  }
}

function get_data(country, callback) {
  var url = 'https://tester-site.nl/api/?key='+apikey+'&type=station&country=' + country;

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          callback(xmlhttp.responseText);
      }
  };

  xmlhttp.open("GET", url, true);
  xmlhttp.send();
}

function handleCountryStations(stations){
  resp = JSON.parse(stations);

  for (i = 0; i < resp.data.length; i++) {
    handleStation(resp.data[i]);
  }
}

// lon, lat, desc, id
function handleStation(data){
  var type = 'PRCP';
  var stn = data.stn;

  getStationData(data, type, placeMarker);



}


function getStationData(data, type, callback){
  var url = 'https://tester-site.nl/api/?key='+ apikey+ '&type=data&stn='+ data.stn + '&var='+ type + '&date=31-1-2019&time=14:0:0';

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          callback(xmlhttp.responseText, data);
      }
  };

  xmlhttp.open("GET", url, true);
  xmlhttp.send();
}

function placeMarker(value, data){
    resp = JSON.parse(value);

    L.marker([data.latitude, data.longitude]).addTo(map)
    .bindPopup('Name: ' + data.name + '<br>ID: ' + data.stn + '<br> Snowfall: ' + resp.data.value);

}
