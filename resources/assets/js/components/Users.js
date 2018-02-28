/**
 * Created by aksha on 2/27/2018.
 */
/**
 * Created by aksha on 2/27/2018.
 */
import React from 'react'
import ReactDOM from 'react-dom';
import axios from 'axios'

// Makes get request to server for all users on site
class Users extends React.Component {
    constructor() {

        super();

        this.state = {
            users: [],
        }
    }

    componentDidMount() {
        axios.get('http://localhost:8000/api/users')
            .then(response => {
                this.setState({ users: response.data });
            })
            .catch(function (error) {
                console.log(error);
            })
    }

    renderUsers() {
        return this.state.users.map(user => {
            return (
                <li key={user.id} >
                    { user.name }
                </li>
            );
        })
    }

    render() {
        return(
            <div>
                <h3>List of Users (email)</h3>
                <ul>
                    {this.renderUsers()}
                </ul>
            </div>
        )
    }
}

export default Users
