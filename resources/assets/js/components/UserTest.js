/**
 * Created by aksha on 7/19/2018.
 */
import React from 'react'
import ReactDOM from 'react-dom';
import axios from 'axios'
import {getAllUsers, passwordResetEmail} from './helpers'

// Makes get request to server for all users on site
class Users extends React.Component {
    constructor() {

        super();

        this.state = {
            getAll_result: null,
        }
    }

    componentDidMount() {
        let comp = this;
    }

    getAll() {
        let res = null;
        getAllUsers().then(function (resp) {
            res = resp;
        });
    }


    render() {
        return(
            <div>
                <h1>User Tests</h1>
                <button onClick={getAll.bind(this)}>Get</button>
            </div>
        )
    }
}

export default Users
