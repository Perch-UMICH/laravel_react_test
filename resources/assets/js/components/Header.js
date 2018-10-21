/**
 * Created by aksha on 2/25/2018.
 */
import React from 'react'
import { Link } from 'react-router-dom'
import { GoogleLogin } from 'react-google-login';


class Header extends React.Component {
    constructor() {
        super();
    }

   responseGoogle() {
        console.log(response);
    };

    render() {
        return (
            <header>
                <h1>Perch API test suite</h1>
                <nav>
                    <ul>
                        <li><Link to='/'>Home</Link></li>
                        <li><Link to='/users'>Users</Link></li>
                    </ul>
                </nav>
            </header>
        )
    }
}

export default Header
