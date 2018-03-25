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
                <nav>
                    <ul>
                        <li><Link to='/'>Home</Link></li>
                        <li><Link to='/users'>Users</Link></li>
                        <li><Link to='/students'>Students</Link></li>
                        <li><Link to='/faculties'>Faculties</Link></li>
                        <li><Link to='/labs'>Labs</Link></li>
                        <li><Link to='/register'>Register</Link></li>
                        <li><Link to='/login'>Login</Link></li>
                        {/*<li><a href={'login/google'}><img width={191} height={46} src={'../../images/btn_google_signin_dark_normal_web@2x.png'} /></a></li>*/}
                        <GoogleLogin
                            clientId="648140670160-klncvki6qbkr47iteo8995fp4j4elv6g.apps.googleusercontent.com"
                            buttonText="Login"
                            onSuccess={this.responseGoogle}
                            onFailure={this.responseGoogle}
                        />
                    </ul>
                </nav>
            </header>
        )
    }
}

export default Header
