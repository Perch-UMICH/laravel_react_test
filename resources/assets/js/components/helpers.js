/**
 * Created by aksha on 2/28/2018.
 */

import React from "react";
import { Redirect } from "react-router-dom";
import axios from 'axios';

// Redirect to login page if not logged in, or continue
export function requireAuth() {
    if(!localStorage.getItem('user_token')) {
        return <Redirect to='/login'  />
    }
}

export function registerUser(name, email, password, password_confirmation) {
    console.log(name + ' ' + email + ' ' + password + ' ' + password_confirmation);
    axios.post('api/register', {
        name,
        email,
        password,
        password_confirmation
    })
        .then(response=> {
            console.log(response.data.message);
            return successfulReg(response);
        })
        .catch(error=> {
            console.error('Error in registration');
            console.error(error);
            return false;
        });
}

function successfulReg(response_in) {
    console.log(response_in);
    localStorage.setItem('user_logged_in', true);
    localStorage.setItem('user_token', response_in.data.result.token);

    return setUserDetails(response_in.data.result.token);
}

export function loginUser(email, password) {
    logoutUser();
    // Login
    console.log('logging in ' + email);
    console.log(password);

    axios.post('api/login', {
        email, password
    })
        .then(response => {
            localStorage.setItem('user_token', response.data.success.token);
            localStorage.setItem('user_logged_in', true);
            console.log('Successfully logged in');
            setUserDetails(email, password, response.data.success.token);
        })
        .catch(error => {
            console.error('Log in unsuccessful');
            console.error(error.response);
            return false;
        });

    return true;
}

function setUserDetails(token) {
    // Save user details to local storage
    axios.post('api/details',
        {
            headers: {
                'Authorization': 'Bearer ' + token,
            }
        }
    )
        .then(response => {
            localStorage.setItem('user_name', response.data.result.name);
            localStorage.setItem('user_id', response.data.result.id);
            localStorage.setItem('user_email', response.data.result.email);
            console.log(response.data.message);
        })
        .catch(error => {
            console.error(error);
            console.error('Could not get user details');
            return false;
        });

    return true;
}

export function logoutUser() {
  // Clear all user vars in local storage
    localStorage.removeItem('user_logged_in');
    localStorage.removeItem('user_email');
    localStorage.removeItem('user_name');
    localStorage.removeItem('user_id');

    axios.post('api/logout',
        {
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('user_token'),
            }
        }
    )
        .then(response => {
            localStorage.removeItem('user_token');
            console.log(response.data.message);
            return true;
        })
        .catch(error => {
            console.error(error);
            console.error('Could not logout');
            return false;
        });
}
