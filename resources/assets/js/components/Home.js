/**
 * Created by aksha on 2/25/2018.
 */
import React from 'react'
import ReactDOM from 'react-dom';
import axios from 'axios'
import {uploadPic} from './helpers.js'
import FormData from 'form-data'


class Home extends React.Component {

    componentDidMount() {

    }

    sendImage() {
        const fileInput = document.getElementById('fileToUpload'.files[0]);
        const formData = new FormData();
        formData.append( 'image', fileInput );

        formData.append('type', 'student');
        formData.append('id', 1);
        console.log(formData);
        const config = {
            headers: { 'content-type': 'multipart/form-data' }
        };

        axios.post('api/pics', formData, config)
            .then(response => {
                console.log(response.data.message);
                console.log(response.data.result);
            })
            .catch(function (error) {
                console.log(error);
            })
    }

    render() {
        return (
            <div>
                <h1>Home page</h1>
                    <input type="file" name="fileToUpload" id="fileToUpload"></input>
                    <button onClick={this.sendImage}>Submit</button>
            </div>
        )
    }
}

export default Home
