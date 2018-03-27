
import React from 'react'
import ReactDOM from 'react-dom';
import axios from 'axios'
import {getAllLabs, getLab, getLabSkills, getLabTags, getLabPreferences} from './helpers'


class Labs extends React.Component {
    constructor() {

        super();

        this.state = {
            labs: [],
            lab: [],
            skills: [],
            tags: [],
            prefs: []
        }
    }

    componentDidMount() {
        let comp = this;

        getAllLabs().then(function (resp) {
            comp.setState({labs: JSON.stringify(resp)});
        });

        getLab(2).then(function (resp) {
            comp.setState({lab: JSON.stringify(resp)});
        });

        getLabSkills(1).then(function (resp) {
            comp.setState({skills: JSON.stringify(resp)});
        });

        getLabTags(2).then(function (resp) {
            comp.setState({tags: JSON.stringify(resp)});
        });

        getLabPreferences(1).then(function (resp) {
            comp.setState({prefs: JSON.stringify(resp)});
        });

    }

    renderStudents() {

    }

    render() {
        return(
            <div>
                <h3>All labs</h3>
                <ul>
                    {this.state.labs}
                </ul>
                <h3>Lab with id 2</h3>
                <ul>
                    {this.state.lab}
                </ul>
                <h3>Skills of lab with id 1</h3>
                <ul>
                    {this.state.skills}
                </ul>
                <h3>Tags of lab with id 2</h3>
                <ul>
                    {this.state.tags}
                </ul>
                <h3>Prefs of lab with id 1</h3>
                <ul>
                    {this.state.prefs}
                </ul>
            </div>
        )
    }
}

export default Labs
