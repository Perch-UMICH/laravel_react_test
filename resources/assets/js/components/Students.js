/**
 * Created by aksha on 2/27/2018.
 */
import React from 'react'
import ReactDOM from 'react-dom';
import axios from 'axios'
import {getAllStudents, getStudent, getStudentSkills, getStudentTags, createStudent, updateStudent,
    addTagsToStudent, removeTagsFromStudent,
    addSkillsToStudent, removeSkillsFromStudent} from './helpers'


class Students extends React.Component {
    constructor() {

        super();

        this.state = {
            students: []
        }
    }

    componentDidMount() {
        let comp = this;

        getAllStudents().then(function (resp) {
            comp.setState({students: JSON.stringify(resp)});
        });
    }

    renderStudents() {

    }

    create() {
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

            </div>
        )
    }
}

export default Students
