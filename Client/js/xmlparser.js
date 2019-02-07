var type_conversion = [
  "STN",
  "COUNTRY",
  "STATION",
  "PRCP"
];

function getXMLMeasurements(measurements) {
    var xml = new XMLSerializer();
    var root = document.createElement("xml");
    root.setAttribute("indent", "yes");
    var measurements = document.createElement("WEATHERDATA");
    var measurement = addElement("MEASUREMENT");
    for (var x = 0; x < measurements.length; x++) {
        for (var y = 0; y < measurements[i].length; y++) {
            measurement.appendChild(addAttribute(type_conversion[y], measurements[x][y]]));
        }
        measurements.appendChild(measurement);
    }
    root.appendChild(measurements);
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
