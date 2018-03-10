/**
 * Created by aksha on 2/27/2018.
 */
/**
 * Created by aksha on 2/27/2018.
 */
import React from 'react'
import ReactDOM from 'react-dom';
import axios from 'axios'
import {getAllUsers} from './helpers'

// Makes get request to server for all users on site
class Users extends React.Component {
    constructor() {

        super();

        this.state = {
            users: [],
        }
    }

    componentDidMount() {
        let comp = this;
        getAllUsers().then(function (resp) {
            comp.setState({users: resp});
        });
    }


    render() {
        return(
            <div>
                <h3>All Users</h3>
                <ul>
                    {this.state.users}
                </ul>
            </div>
        )
    }
}

export default Users
