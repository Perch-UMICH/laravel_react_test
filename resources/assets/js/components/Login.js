/**
 * Created by aksha on 2/27/2018.
 */

/**
 * Created by aksha on 2/27/2018.
 */
import React from 'react'
import ReactDOM from 'react-dom';
import axios from 'axios'
import {loginUser, logoutUser, getUsername, isLoggedIn} from './helpers';


class Login extends React.Component {
    constructor(props) {

        super(props);

        this.state = {
            email: '',
            password: '',
            logged_in: '',
            err: undefined
        }
    }

    componentDidMount() {
        this.setState({logged_in: isLoggedIn()})
    }


    onSubmit(e){
        e.preventDefault();
        const {email, password} = this.state;

        loginUser(email, password);
        this.forceUpdate();
    }


    onChange(e) {
        const {name, value} = e.target;
        this.setState({[name]: value});
    }

    render() {
        let error = this.state.err ;
        let msg = (!error) ? 'Login successful' : 'Error in login' ;

        if (this.state.logged_in) {
            return(
                <div>
                    <h3>You are logged in as {getUsername.bind(this)}</h3>
                    <button onClick={logoutUser.bind(this)}>Logout</button>
                </div>
            )
        }

        return(
            <div>
                <h3>Login</h3>
                <div>
                    {this.state.err != undefined && <div role="alert">{msg}</div>}
                </div>
                <form className="form-horizontal" role="form" method="POST" onSubmit= {this.onSubmit.bind(this)}>


                    <div className="form-group">
                        <label htmlFor="email" className="col-md-4 control-label">E-Mail Address</label>

                        <div className="col-md-6">
                            <input id="email" type="email" className="form-control" ref="email" name="email" onChange={this.onChange.bind(this)} required />
                        </div>
                    </div>

                    <div className="form-group">
                        <label htmlFor="password" className="col-md-4 control-label">Password</label>

                        <div className="col-md-6">
                            <input id="password" type="password" className="form-control"  ref="password" name="password" onChange={this.onChange.bind(this)} required/>
                        </div>
                    </div>

                    <div className="form-group">
                        <div className="col-md-6 col-md-offset-4">
                            <button type="submit" className="btn btn-primary">
                                Login
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        )
    }
}

export default Login
