/**
 * Created by aksha on 2/25/2018.
 */
import React from 'react'
import { Switch, Route } from 'react-router-dom'
import Labs from './Labs'
import Faculties from './Faculties'
import Example from './Example'
import Students from './Students'
import Register from './Register'
import Users from './Users'
import Home from './Home'
import Login from './Login'
import PositionTest from './PositionTest'
import FileUploadTest from './FileUploadTest'

const Main = () => (
    <main>
        <Switch>
            <Route exact path='/' component={Home}/>
            <Route path='/user_test' component={UserTest}/>
            <Route path='/lab_test' component={LabTest}/>
            <Route path='/position_test' component={PositionTest}/>
            <Route path='/application_test' component={ApplicationTest}/>
            <Route path='/file_upload_test' component={FileUploadTest}/>
        </Switch>
    </main>
);

export default Main
