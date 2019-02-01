import React, {Component} from 'react';

import CanvasJSReact from '../Map/canvasjs.react';
import { CanvasJS } from '../Map/canvasjs.react';
import { CanvasJSChart} from '../Map/canvasjs.react';

export class GraphComponent extends Component {
    displayname = GraphComponent.name

    constructor(props){
        super(props);

        this.onClickGoBack = this.onClickGoBack.bind(this);

    }

    onClickGoBack(){
        window.location = '/';
    }

    renderHomePage(){
        const options = {
            animationEnabled: true,
            title:{
                text: "Monthly Sales - 2017"
            },
            axisX: {
                valueFormatString: "MMM"
            },
            axisY: {
                title: "Sales (in USD)",
                prefix: "$",
                includeZero: false
            },
            data: [{
                yValueFormatString: "$#,###",
                xValueFormatString: "MMMM",
                type: "spline",
                dataPoints: [
                    { x: new Date(2017, 0), y: 25060 },
                    { x: new Date(2017, 1), y: 27980 },
                    { x: new Date(2017, 2), y: 42800 },
                    { x: new Date(2017, 3), y: 32400 },
                    { x: new Date(2017, 4), y: 35260 },
                    { x: new Date(2017, 5), y: 33900 },
                    { x: new Date(2017, 6), y: 40000 },
                    { x: new Date(2017, 7), y: 52500 },
                    { x: new Date(2017, 8), y: 32300 },
                    { x: new Date(2017, 9), y: 42000 },
                    { x: new Date(2017, 10), y: 37160 },
                    { x: new Date(2017, 11), y: 38400 }
                ]
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
                <button onClick={this.onClickGoBack}>Go back!</button>
            </div>
        );
    }

    render() {
        let content = this.renderHomePage()
        return (
            <div>
                {content}
            </div>
        );
    }
}

export default GraphComponent;
