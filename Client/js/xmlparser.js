
function getXMLMeasurements() {
    var xml = new XMLSerializer();
    var root = document.createElement("xml");
    root.setAttribute("indent", "yes");
    var measurements = document.createElement("WEATHERDATA");
    var measurement = addElement("MEASUREMENT");
    measurement.appendChild(addAttribute("STN", "133762"));
    measurement.appendChild(addAttribute("DATE", "10-10-10"));
    measurement.appendChild(addAttribute("TIME", "10:10:10"));
    measurements.appendChild(measurement);
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
    return element
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
