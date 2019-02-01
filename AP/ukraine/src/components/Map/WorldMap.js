import React, {Component} from 'react';
import Leaflet from 'leaflet';
import { Map, Marker, Popup, TileLayer } from 'react-leaflet';
import 'leaflet/dist/leaflet.css'
import { CanvasJSChart} from './canvasjs.react';

Leaflet.Icon.Default.imagePath = '//cdnjs.cloudflare.com/ajax/libs/leaflet/1.0.0/images/';

export const mapConfig = {
    center: [48.379433, 31.1655799],
    zoom: 6
};

export class WorldMap extends Component {
    displayname = WorldMap.name;
    dataArray = [];
    newDate = new Date();

    constructor(props){
        super(props);
        this.state = {
            stationNr: null,
            dataStations: [],
            dataDetails: [],
            callDate: null
        }

        fetch('https://tester-site.nl/api/?key=asf756saf5asf75a7s6f&type=station&country=ukraine')
            .then(response => response.json())
            .then(data => this.setState({ dataStations: data }));

        this.getOneHour = this.getOneHour.bind(this);
        this.onClickStation = this.onClickStation.bind(this);
        this.keepCallingDetails = this.keepCallingDetails.bind(this);
    }

    onClickStation(stationNumber){
        let stn = stationNumber;
        var date = new Date();
        var day = date.getDate();
        var month = date.getMonth() + 1;
        var year = date.getFullYear();
        var newDate = day + "-" + month + "-" + year;
        var hours = date.setHours(15);
        var minutes = date.setMinutes(10);
        var seconds = date.setSeconds(0);
        var time = date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();
        this.dataArray = [];
        //todo kijk de seconden en minuten na op 2x 0
        let url = 'https://tester-site.nl/api/?key=asf756saf5asf75a7s6f&type=data&stn=' + stn + '&var=dewp&date=31-1-2019&time=14:10:0';

        // var newdate = new Date(date.getFullYear(), date.getMonth(), date.getDate(), date.getHours(), date.getMinutes(), date.getSeconds());
        this.newDate = new Date(2019, 0, 31, 14, 10, 0);


        let array = this.state.dataDetails;
        fetch(url)
            .then(response => response.json())
            .then(data =>{
                this.setState({
                stationNr: stationNumber
                });
        });

        // Fill graph!
        // this.getOneHour(stationNumber);

        // //setInterval(this.keepCallingDetails, 10000);
        setInterval(this.keepCallingDetails, 1000);
    }

    getOneHour(stationNr){
      // Get reference of old date
      let oldData = new Date(this.newDate.getTime());

      var hour = oldData.getHours();
      var minute = oldData.getMinutes();
      var second = oldData.getSeconds();



      var baseUrl = 'https://tester-site.nl/api/?key=asf756saf5asf75a7s6f&type=data&stn=' + stationNr;

      // Use current time - 60 minutes
      // use current seconds - 60 seconds

      for(var m = 0; m < 60; m++){
        for(var s = 0; s < 6; s++){
          var dateUrl = '&var=dewp&date=31-1-2019' + '&time=' + hour + ":"+m+":"+ s*10;
          let myDate = new Date(oldData.getTime());
          myDate.setMinutes(m);
          myDate.setSeconds(s*10);
          var url = baseUrl + dateUrl;

          fetch(url)
              .then(response => response.json())
              .then(data =>{
                  this.dataArray.push({ x: myDate, y: data.data.value });
          });

        }
      }

      this.setState({
          dataDetails: this.dataArray
      });

      // For loop 60 minutes
      // for loop 6 (*10) seconds
      // create url
      //
    }


    keepCallingDetails(){
        let date = this.newDate;

        //console.log(date)

        date.setSeconds(date.getSeconds() + 10);

        if (date.getSeconds() >= 60) {
            date.setMinutes(date.getMinutes() + 1);
            date.setSeconds(0);
        }
        if (date.getMinutes() >= 60){
            date.setHours(date.getHours() + 1);
            date.setMinutes(0);
        }

        // this.setState({
        //     callDate: date
        // });

        //let postDate = this.state.callDate.getDate() + "-" + (this.state.callDate.getMonth() + 1) + "-" + this.state.callDate.getFullYear();

        let url = '';
        // if (this.state.callDate.getSeconds().toString() === '00' && this.state.callDate.getMinutes().toString() === '00') {
        //     url = 'https://tester-site.nl/api/?key=asf756saf5asf75a7s6f&type=data&stn=' + this.state.stationNr + '&var=dewp&date=31-1-2019'
        //         + '&time=' + this.state.callDate.getHours() + ":0:0";
        // } else if (this.state.callDate.getSeconds().toString() === '00'){
        //     url = 'https://tester-site.nl/api/?key=asf756saf5asf75a7s6f&type=data&stn=' + this.state.stationNr + '&var=dewp&date=31-1-2019'
        //         + '&time=' + this.state.callDate.getHours() + ":" + this.state.callDate.getMinutes() + ":0";
        // } else if (this.state.callDate.getMinutes().toString() === '00') {
        //     url = 'https://tester-site.nl/api/?key=asf756saf5asf75a7s6f&type=data&stn=' + this.state.stationNr + '&var=dewp&date=31-1-2019'
        //         + '&time=' + this.state.callDate.getHours() + ':0:' + this.state.callDate.getSeconds();
        // } else {
        //     url = 'https://tester-site.nl/api/?key=asf756saf5asf75a7s6f&type=data&stn=' + this.state.stationNr + '&var=dewp&date=31-1-2019'
        //         + '&time=' + this.state.callDate.getHours() + ':' + this.state.callDate.getMinutes() + ':' + this.state.callDate.getSeconds();
        // }

        // url = 'https://tester-site.nl/api/?key=asf756saf5asf75a7s6f&type=data&stn=' + this.state.stationNr +
        // '&var=dewp&date=31-1-2019' + '&time=' + this.date.getHours() + ":0:0";

        var hour = date.getHours();
        var minute = date.getMinutes();
        var second = date.getSeconds();

        var baseUrl = 'https://tester-site.nl/api/?key=asf756saf5asf75a7s6f&type=data&stn=' + this.state.stationNr;
        var dateUrl = '&var=dewp&date=31-1-2019' + '&time=' + hour + ":"+minute+":"+second;

        url = baseUrl + dateUrl;
        // console.log(url);
        // console.log(hour + '-' + minute +'-'+second);


        var array = this.state.dataDetails;

        var yAxis = null;

        date = new Date(date.getTime());

        fetch(url)
            .then(response => response.json())
            .then(data =>{
                this.dataArray.push({ x: date, y: data.data.value });
        });

        this.setState({
            dataDetails: this.dataArray
        });
    }

    renderHomePage(){
        if (this.state.dataDetails != null){
            const options = {
                animationEnabled: true,
                title:{
                    text: "This is the graph of station " + this.state.stationNr
                },
                axisX: {
                    valueFormatString: "hh:mm:ss"
                },
                axisY: {
                    title: "Dewpoint",
                    prefix: "°C",
                    includeZero: true
                },
                data: [{
                    yValueFormatString: "#.#",
                    xValueFormatString: "hh:mm:ss",
                    type: "spline",
                    dataPoints: this.dataArray
                }]
            }
            return (
                <div>
                    <CanvasJSChart
                        options={options}
                        /* onRef = {ref => this.chart = ref} */
                    />
                    <br/>
                    <br/>
                </div>
            );
        }
    }

    render() {
        const markers = [];
        if (this.state.dataStations.data !== undefined) {
            let i;
            for (i = 0; i < this.state.dataStations.data.length; i++) {
                markers.push({
                    name: this.state.dataStations.data[i].name,
                    stn: this.state.dataStations.data[i].stn,
                    latlng: [this.state.dataStations.data[i].latitude, this.state.dataStations.data[i].longitude]
                });
            }
        }

        let content = this.renderHomePage();
        // create an array with marker components
        const LeafletMarkers = markers.map(marker => (
            <Marker position={marker.latlng} key={`marker_${marker.name}`}>
                <Popup>
                    <div>
                        <p>{marker.name}</p>
                        <p>{marker.stn}</p>
                        <button onClick={() => this.onClickStation(marker.stn)}>Click me</button>
                    </div>
                </Popup>
            </Marker>
        ));

        return (
            <div>
                <div className="map">
                    <Map center={mapConfig.center} zoom={mapConfig.zoom} className="map__reactleaflet">
                        <TileLayer
                            url="https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png"
                            attribution='&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, &copy; <a href="https://carto.com/attribution">CARTO</a>'
                        />
                        {LeafletMarkers}
                    </Map>
                </div>
                <br/>
                <br/>
                <div>
                    {content}
                </div>
            </div>
        );
    }
}
