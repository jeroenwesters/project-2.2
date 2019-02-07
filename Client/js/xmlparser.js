var type_conversion = [
  "STN",
  "COUNTRY",
  "STATION",
  "PRCP"
];

function getXMLMeasurements() {
    var measurements = stationData;
    var xml = new XMLSerializer();
    var root = document.createElement("xml");
    root.setAttribute("indent", "yes");
    var measurementsNode = document.createElement("WEATHERDATA");
    var measurementNode = addElement("MEASUREMENT");

    for (var x = 0; x < measurements.length; x++) {
        measurementNode.appendChild(type_conversion[0], measurements[x].stn);
        measurementNode.appendChild(type_conversion[1], measurements[x].country);
        measurementNode.appendChild(type_conversion[2], measurements[x].station);
        measurementNode.appendChild(type_conversion[3], measurements[x].value);
        measurementsNode.appendChild(measurementNode);
    }
    root.appendChild(measurementsNode);
    var doc = "<?xml version='1.0' indent='yes'?>";
    doc += xml.serializeToString(root);
    getXML("Measurements.xml", doc);
}

function addAttribute(name, value) {
    element = document.createElement(name);
    element.innerHTML = value;
    return element;
}

function addElement(name) {
    element = document.createElement(name);
    return element;
}

function getXML(filename, text) {
    var pom = document.createElement('a');
    pom.setAttribute('href', 'data:text/xml; charset=utf-8,' + encodeURIComponent(text));
    pom.setAttribute('download', filename);

    if (document.createEvent) {
        var event = document.createEvent('MouseEvents');
        event.initEvent('click', true, true);
        pom.dispatchEvent(event);
    }
    else {
        pom.click();
    }
}
