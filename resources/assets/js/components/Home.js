/**
 * Created by aksha on 2/25/2018.
 */
import React from 'react'
import ReactDOM from 'react-dom';
import axios from 'axios'
import {uploadPic, uploadResume} from './helpers.js'
import FormData from 'form-data'


class Home extends React.Component {

    componentDidMount() {

    }

    sendImage() {
        uploadPic('student', 1, 'fileToUpload')
    }

    sendResume() {
        uploadResume(1, 'fileToUpload');
    }

    render() {
        return (
            <div>
                <h1>Home page</h1>
                    <input type="file" name="fileToUpload" id="fileToUpload"></input>
                    <button onClick={this.sendImage}>Submit Pic</button>
                    <button onClick={this.sendResume}>Submit Resume</button>
            </div>
        )
    }
}

export default Home
