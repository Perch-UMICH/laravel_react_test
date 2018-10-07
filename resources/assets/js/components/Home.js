/**
 * Created by aksha on 2/25/2018.
 */
import React from 'react'
import ReactDOM from 'react-dom';
import axios from 'axios'
import {uploadUserFile} from './helpers.js'
import FormData from 'form-data'


class Home extends React.Component {

    componentDidMount() {

    }

    sendFile() {
        loginUser('akshayro@umich.edu','password').then( function(resp) {
            let file = document.getElementById('fileToUpload').files[0];
            let formData = new FormData();
            formData.append('file', file);
            uploadUserFile(formData, 'resume');
        })
    }


    render() {
        return (
            <div>
                <h1>Home page</h1>
                    <input type="file" name="fileToUpload" id="fileToUpload"></input>
                    <button onClick={this.sendFile}>Submit File</button>
            </div>
        )
    }
}

export default Home
