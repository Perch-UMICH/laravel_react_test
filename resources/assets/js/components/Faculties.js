
import React from 'react'
import ReactDOM from 'react-dom';
import axios from 'axios'
import {getAllFaculties, getFaculty, getFacultyLabs} from './helpers'


class Faculties extends React.Component {
    constructor() {

        super();

        this.state = {
            faculties: [],
            faculty: [],
            labs: []
        }
    }

    componentDidMount() {
        let comp = this;

        getAllFaculties().then(function (resp) {
            comp.setState({faculties: JSON.stringify(resp)});
        });

        getFaculty(2).then(function (resp) {
            comp.setState({faculty: JSON.stringify(resp)});
        });

        getFacultyLabs(1).then(function (resp) {
            comp.setState({labs: JSON.stringify(resp)});
        });

    }

    renderStudents() {

    }

    render() {
        return(
            <div>
                <h3>All faculty</h3>
                <ul>
                    {this.state.faculties}
                </ul>
                <h3>Faculty with id 2</h3>
                <ul>
                    {this.state.faculty}
                </ul>
                <h3>Labs of faculty with id 1</h3>
                <ul>
                    {this.state.labs}
                </ul>
            </div>
        )
    }
}

export default Faculties
