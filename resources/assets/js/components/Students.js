/**
 * Created by aksha on 2/27/2018.
 */
import React from 'react'
import ReactDOM from 'react-dom';
import axios from 'axios'


class Students extends React.Component {
    constructor() {

        super();

        this.state = {
            students: [],
        }
    }

    componentDidMount() {
        axios.get('http://localhost:8000/api/students')
            .then(response => {
                this.setState({ students: response.data });
            })
            .catch(function (error) {
                console.log(error);
            })
    }

    renderStudents() {
        return this.state.students.map(student => {
            return (
                <li key={student.id} >
                    { student.first_name }
                    { student.last_name }
                </li>
            );
        })
    }

    render() {
        return(
            <div>
                <h3>List of Students (first name, last name)</h3>
                <ul>
                    {this.renderStudents()}
                </ul>
            </div>
        )
    }
}

export default Students
