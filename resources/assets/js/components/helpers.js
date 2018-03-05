/**
 * Created by aksha on 2/28/2018.
 */

import React from "react";
import { Redirect } from "react-router-dom";
import axios from 'axios';


// Redirect to login page if not logged in, or continue
export function isLoggedIn() {
    if(!localStorage.getItem('user_logged_in')) {
        console.log('Not logged in');
        return false;
    }
    console.log('Logged in');
    return true;
}

export function registerUser(name, email, password, password_confirmation) {
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
    localStorage.setItem('user_logged_in', true);
    localStorage.setItem('user_token', response_in.data.result.token);

    return setUserDetails(response_in.data.result.token);
}

export function loginUser(email, password) {
    // Clear all user vars in local storage
    localStorage.removeItem('user_logged_in');
    localStorage.removeItem('user_email');
    localStorage.removeItem('user_name');
    localStorage.removeItem('user_id');

    // Login
    console.log('logging in ' + email);
    console.log(password);

    axios.post('api/login', {
        email, password
    })
        .then(response => {
            //localStorage.setItem('user_token', response.data.result.token);
            localStorage.setItem('user_logged_in', true);
            localStorage.setItem('user_name', response.data.result.name);
            localStorage.setItem('user_id', response.data.result.id);
            localStorage.setItem('user_email', response.data.result.email);
            console.log('Successfully logged in');
            //setUserDetails(response.data.result.token);
        })
        .catch(error => {
            console.error('Log in unsuccessful');
            console.error(error);
            return false;
        });

    return true;
}

function setUserDetails(token) {
    console.log(token);
    // Save user details to local storage
    axios.post('api/details',
        {
            headers: {
                'Authorization': 'Bearer ' + token
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

export function logoutCurrentUser() {
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

// Users

export function getAllUsers() {
    console.log('Getting users');
    return axios.get('api/users')
        .then(response => {
            return response.data
        })
        .catch(function (error) {
            console.log(error);
            return [];
        })
}

export function getCurrentUserEmail() {
    return localStorage.getItem('user_email');
}

export function getCurrentUserId() {
    return localStorage.getItem('user_id');
}

export function getCurrentUsername() {
    return localStorage.getItem('user_name');
}

// Students

export function getAllStudents() {
    console.log('Getting students');
    return axios.get('api/students')
        .then(response => {
            return response.data
        })
        .catch(function (error) {
            console.log(error);
            return [];
        })
}

export function getStudent(student_id) {
    console.log('Getting student');
    return axios.get('api/students/' + student_id)
        .then(response => {
            console.log(response.data.message);
            return response.data.result;
        })
        .catch(function (error) {
            console.log(error);
            return [];
        })
}

export function createStudent(user_id, first_name, last_name, major, year, gpa, email) {
    console.log('Creating student');
    return axios.post('api/students/', [user_id, first_name, last_name, major, year, gpa, email])
        .then(response => {
            console.log(response.data.message);
            return response.data.result;
        })
        .catch(function (error) {
            console.log(error);
            return [];
        })
}

export function updateStudent(user_id, first_name, last_name, major, year, gpa, email) {
    console.log('Updating student');
    let _method = 'PUT';
    return axios.post('api/students/', {_method, user_id, first_name, last_name, major, year, gpa, email})
        .then(response => {
            console.log(response.data.message);
            return response.data.result;
        })
        .catch(function (error) {
            console.log(error);
            return [];
        })
}

export function deleteStudent(student_id) {
    console.log('Deleting student');
    return axios.delete('api/students/' + student_id)
        .then(response => {
            console.log(response.data.message);
            return response.data.result;
        })
        .catch(function (error) {
            console.log(error);
            return [];
        })
}

export function getStudentSkills(student_id) {
    console.log('Getting student skills');
    return axios.get('api/students/' + student_id + '/skills')
        .then(response => {
            console.log(response.data.message);
            return response.data.result;
        })
        .catch(function (error) {
            console.log(error);
            return [];
        })
}

export function getStudentTags(student_id) {
    console.log('Getting student tags');
    return axios.get('api/students/' + student_id + '/tags')
        .then(response => {
            console.log(response.data.message);
            return response.data.result;
        })
        .catch(function (error) {
            console.log(error);
            return [];
        })
}

// Faculties