/**
 * Created by aksha on 2/27/2018.
 */
import React from 'react'
import ReactDOM from 'react-dom';
import axios from 'axios'
import {getAllStudents, getStudent, getStudentSkills, getStudentTags, updateStudent, addTagsToStudent, removeTagsFromStudent} from './helpers'


class Students extends React.Component {
    constructor() {

        super();

        this.state = {
            students: [],
            student: [],
            skills: [],
            tags: []
        }
    }

    componentDidMount() {
        let comp = this;

        getAllStudents().then(function (resp) {
            comp.setState({students: JSON.stringify(resp)});
        });

        getStudent(2).then(function (resp) {
            comp.setState({student: JSON.stringify(resp)});
        });

        getStudentSkills(1).then(function (resp) {
            comp.setState({skills: JSON.stringify(resp)});
        });

        getStudentTags(1).then(function (resp) {
            comp.setState({tags: JSON.stringify(resp)});
        });
    }

    renderStudents() {

    }

    update() {
        updateStudent(1, 'test', 'test', null, null, null, null, null, null, null).then(function (resp) {
            console.log(resp);
        });
    }

    add_tags() {
        addTagsToStudent(1, [5, 6]).then(function (resp) {
            console.log(resp);
        });
    }

    remove_tags() {
        removeTagsFromStudent(1, [5, 6]).then(function (resp) {
            console.log(resp);
        })
    }

    render() {
        return(
            <div>
                <h3>All students</h3>
                <ul>
                    {this.state.students}
                </ul>
                <h3>Student with id 2</h3>
                <ul>
                    {this.state.student}
                </ul>
                <h3>Skills of student with id 1</h3>
                <ul>
                    {this.state.skills}
                </ul>
                <h3>Tags of student with id 1</h3>
                <ul>
                    {this.state.tags}
                </ul>
                <button onClick={this.update}>Update</button>
                <button onClick={this.add_tags}>Add Tags</button>
                <button onClick={this.remove_tags}>Remove Tags</button>
            </div>
        )
    }
}

export default Students
