// Made by Jeroen - © 2019

var apikey = "";

var year = 2019;
var month = 1;
var day = 1;
var sec = 0;
var min = 0;
var hour = 0;

var counter = 0;
var counterComplete = 0;

var stationData;

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

  dateTime.innerHTML = 'Measurement on: ' + formatNumber(day) + '-' + formatNumber(month) + '-' + formatNumber(year) + ' --- ' + formatNumber(hour) + ':' + formatNumber(min) + ':' + formatNumber(sec * 10);

}


function loadData(key){
  stationData = new Array();
  apikey = key;
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
          callback(country, xmlhttp.responseText);
      }
  };

  xmlhttp.open("GET", url, true);
  xmlhttp.send();
}

function handleCountryStations(country, stations){
  resp = JSON.parse(stations);

  for (i = 0; i < resp.data.length; i++) {
    handleStation(country, resp.data[i]);
  }
}

// lon, lat, desc, id
function handleStation(country, data){
    var type = 'prcp';

    counter++;

    getStationData(data, country, type, onComplete);

}


function getStationData(data, country, type, callback){
  //var url = 'https://tester-site.nl/api/?key='+ apikey+ '&type=data&stn='+ data.stn + '&var='+ type + '&date=31-1-2019&time=14:0:0';
  var time = (hour + ':' + min + ':' + sec);
  var date = (day + '-' + month + '-' + year);

  var time = "14:0:10";
  var date = "31-1-2019";

  var url = 'https://tester-site.nl/api/?key='+ apikey+ '&type=data&stn='+ data.stn + '&var='+ type + '&date=' + date + '&time='+time;

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
     if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         callback(data, country, type, xmlhttp.responseText);
     }
  };

  xmlhttp.open("GET", url, true);
  xmlhttp.send();
}




function onComplete(data, country, type, resp){
  resp = JSON.parse(resp);

  // stationData.push([]);

  var val = parseFloat(Math.round(resp.data.value * 100) / 100).toFixed(2);

  stationData.push({stn: data.stn, country: country, station: data.name, value: val});

  counterComplete++;

  if(counterComplete == counter){
    // Then we can calculate
    order();
  }
}

function order(){

  // Not working!
  stationData.sort(function(a,b) {
    return b.value - a.value;
  });

  //console.log(stationData);
  createData(stationData);
}

function createData(top){
  // get top (for now removed)
  table = document.getElementById("topten");

  var row = ''

  // cells creation
  for (var j = 0; j <= top.length; j++) {
    // table row creation
    var data = top[j];

    var row = document.createElement("tr");

    appendCell(j+1, row);
    appendCell(data.country, row);
    appendCell(data.station, row);
    appendCell(data.value, row);

    //row added to end of table body


    table.appendChild(row);


    if(j == 9){
      break;
    }
  }

  var el = document.getElementById("loading");
    el.style.visibility = "hidden";
  }

function appendCell(content, parent){

  var id = document.createElement("td");
  var idContent = document.createTextNode(content);
  id.appendChild(idContent);
  parent.appendChild(id);
}



function formatNumber(number){
  if(number < 10){
    return '0' + number;
  }else{
    return number;
  }

}
