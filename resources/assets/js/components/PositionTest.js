/**
 * Created by aksha on 4/21/2018.
 */

/**
 * Created by aksha on 2/27/2018.
 */
import React from 'react'
import ReactDOM from 'react-dom';
import axios from 'axios'
import {getAllLabPositions, getLabPosition, createLabPosition, updateLabPosition, deleteLabPosition,
        getPositionApplication, createApplication, updateApplication,
        createApplicationResponse, updateApplicationResponse, deleteApplicationResponse, submitApplicationResponse,
        getLabPositionApplicants} from './helpers'


class PositionTest extends React.Component {
    constructor() {

        super();

        this.state = {
            positions: [],
            position: [],
            application: [],
            responses: []
        }
    }

    componentDidMount() {
        let comp = this;

        getAllLabPositions(1).then(function (resp) {
            comp.setState({positions: JSON.stringify(resp)});
        });

        getLabPosition(1).then(function (resp) {
            comp.setState({position: JSON.stringify(resp)});
        });

        getPositionApplication(1).then(function (resp) {
            comp.setState({application: JSON.stringify(resp)});
        });

        getLabPositionApplicants(1).then(function (resp) {
            comp.setState({responses: JSON.stringify(resp)});
        });
    }

    create_position() {

    }

    create_app() {

    }

    update_app() {

    }


    render() {
        return(
            <div>
                <h3>All positions of Lab 1</h3>
                <ul>
                    {this.state.positions}
                </ul>
                <h3>Position of ID 1</h3>
                <ul>
                    {this.state.position}
                </ul>
                <h3>Application questions to position of ID 1</h3>
                <ul>
                    {this.state.application}
                </ul>
                <h3>All responses to application questions of position ID 1</h3>
                <ul>
                    {this.state.responses}
                </ul>
            </div>
        )
    }
}

export default PositionTest
