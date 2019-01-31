var map = null;

var currentYear = 0;
var currentMonth = 0;
var currentDay = 0;
var currentHour = 0;
var currentMinute = 0;
var currentSecond = 0;

// One hour back
var lastHour = currentHour - 1;
var lastDay = currentDay;
var lastMonth = currentMonth;
var lastYear = currentYear;

// Check hours
if(lastHour < 0){
  // Set last hour
  lastHour = 23;
  // Decrement last day
  lastDay -= 1;

  // Check days
  if(lastDay < 0){
    // Decrement month
    lastMonth -= 1;
    // Set to last day of the month
    lastDay = daysInMonth(currentMonth, currentYear);

    // Check month
    if(currentMonth < 0){
      //Set month
      lastMonth = 12;
      // Decrement year
      lastYear-= 1;
    }
  }
}

// Get last day of the month!
function daysInMonth (month, year) {
    return new Date(year, month, 0).getDate();
}


for (var m = 0; m < 60; m++) {

}


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
