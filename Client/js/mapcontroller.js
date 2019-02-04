// Created by Jeroen - © 2019
var map = null;
var apikey = "";

var year = 2019;
var month = 1;
var day = 1;
var sec = 0;
var min = 0;
var hour = 0;

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
    var type = 'SNDP';
    placeMarker(type, data)
}

function placeMarker(type, data){

    L.marker([data.latitude, data.longitude]).addTo(map)
    .bindPopup('').on("click", function(e){

      var popup = e.target.getPopup();
      getStationData(data, type, popup,updateMarker);
    });
  }


function getStationData(data, type, popup, callback){
  //var url = 'https://tester-site.nl/api/?key='+ apikey+ '&type=data&stn='+ data.stn + '&var='+ type + '&date=31-1-2019&time=14:0:0';
  var time = (hour + ':' + min + ':' + sec * 10);
  var date = (day + '-' + month + '-' + year);

  var url = 'https://tester-site.nl/api/?key='+ apikey+ '&type=data&stn='+ data.stn + '&var='+ type + '&date=' + date + '&time='+time;

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
     if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         callback(data, popup, xmlhttp.responseText);
     }
  };

  xmlhttp.open("GET", url, true);
  xmlhttp.send();
}

function updateMarker(data, popup, resp){
  resp = JSON.parse(resp);

  if(resp.error){
    popup.setContent('Name: ' + data.name + '<br>ID: ' + data.stn + '<br> Snowfall: ' + 'NOT FOUND');
  }else{
    popup.setContent('Name: ' + data.name + '<br>ID: ' + data.stn + '<br> Snowfall: ' + resp.data.value + ' cm');
  }
}

function getTimeDate(){
  var d = new Date();
  d.setSeconds(d.getSeconds() - 20);
  year = d.getFullYear();
  month = d.getMonth();
  day = d.getDate();
  hour = d.getHours();
  min = d.getMinutes();
  sec = d.getSeconds()

  // Add delay for file system
  var timeString = 'Time: ';

  // Get per 10 seconds
  sec = Math.floor(sec/10);

  dateTime = document.getElementById("currentDateTime");

  dateTime.innerHTML = 'Measurement on: ' + formatNumber(day) + '-' + formatNumber(month) + '-' + formatNumber(year) + ' @ ' + formatNumber(hour) + ':' + formatNumber(min) + ':' + formatNumber(sec * 10);
}

function formatNumber(number){
  if(number < 10){
    return '0' + number;
  }else{
    return number;
  }

}
