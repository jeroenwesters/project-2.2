import React, { Component } from 'react';
import { BrowserRouter as Router, Route, Link } from 'react-router-dom';
import './App.css';

import {WorldMap} from "./components/Map/WorldMap";
import {GraphComponent} from "./components/graph/GraphComponent";

class App extends Component {
    render() {
        return (
            <Router>
                <div className="App">
                    <div>
                        <Route exact path="/" component={WorldMap} />
                    </div>
                </div>
            </Router>
        );
    }
}

export default App;