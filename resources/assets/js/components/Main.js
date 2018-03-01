/**
 * Created by aksha on 2/25/2018.
 */
import React from 'react'
import { Switch, Route } from 'react-router-dom'
import Example from './Example'
import Students from './Students'
import Register from './Register'
import Users from './Users'
import Home from './Home'
import Login from './Login';

const Main = () => (
    <main>
        <Switch>
            <Route exact path='/' component={Home}/>
            <Route path='/example' component={Example}/>
            <Route path='/users' component={Users}/>
            <Route path='/students' component={Students}/>
            <Route path='/register' component={Register}/>
            <Route path='/login' component={Login}/>
        </Switch>
    </main>
);

export default Main
