/**
 * Created by aksha on 2/27/2018.
 */
import React from 'react'
import ReactDOM from 'react-dom';
import axios from 'axios'
 import {logoutUser, registerUser} from './helpers';


class Register extends React.Component {
    constructor(props) {

        super(props);

        this.state = {
            name: '',
            email : '',
            password: '',
            password_confirmation: '',
            err: undefined
        }
    }

    componentDidMount() {

    }

    onSubmit(e){
        e.preventDefault();
        const {name, email, password, password_confirmation} = this.state ;

        let no_err = registerUser(name, email, password, password_confirmation);
        if (no_err) {
            this.refs.name.value="";
            this.refs.password.value="";
            this.refs.email.value="";
            this.refs.confirm.value="";
            this.setState({err: false});
        }
        else {
            this.setState({err: true});
        }
        this.forceUpdate();
    }

    onChange(e) {
        const {name, value} = e.target;
        this.setState({[name]: value});
    }

    render() {
        let error = this.state.err ;
        let msg = (!error) ? 'Registered successfully' : 'Error in registration' ;

        if (localStorage.getItem('user_logged_in')) {
            return(
                <div>
                    <h3>You are logged in as {localStorage.getItem('user_name')}</h3>
                    <button onClick={logoutUser.bind(this)}>Logout</button>
                </div>
            )
        }

        return(
            <div>
                <h3>Register</h3>
                <div>
                    {this.state.err != undefined && <div role="alert">{msg}</div>}
                </div>
                <form className="form-horizontal" role="form" method="POST" onSubmit= {this.onSubmit.bind(this)}>

                    <div className="form-group">
                        <label for="name" className="col-md-4 control-label">Name</label>

                        <div className="col-md-6">
                            <input id="name" type="text" className="form-control" ref="name" name="name" onChange={this.onChange.bind(this)} required />
                        </div>
                    </div>

                    <div className="form-group">
                        <label for="email" className="col-md-4 control-label">E-Mail Address</label>

                        <div className="col-md-6">
                            <input id="email" type="email" className="form-control" ref="email" name="email" onChange={this.onChange.bind(this)} required />
                        </div>
                    </div>

                    <div className="form-group">
                        <label for="password" className="col-md-4 control-label">Password</label>

                        <div className="col-md-6">
                            <input id="password" type="password" className="form-control"  ref="password" name="password" onChange={this.onChange.bind(this)} required/>
                        </div>
                    </div>

                    <div className="form-group">
                        <label for="password-confirm" className="col-md-4 control-label">Confirm Password</label>

                        <div className="col-md-6">
                            <input id="password-confirm" type="password" className="form-control" ref="confirm" name="password_confirmation" onChange={this.onChange.bind(this)} required/>
                        </div>
                    </div>

                    <div className="form-group">
                        <div className="col-md-6 col-md-offset-4">
                            <button type="submit" className="btn btn-primary">
                                Register
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        )
    }
}

export default Register
