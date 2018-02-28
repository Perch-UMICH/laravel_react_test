/**
 * Created by aksha on 2/25/2018.
 */
import React from 'react'
import { Link } from 'react-router-dom'


const Header = () => (
    <header>
        <nav>
            <ul>
                <li><Link to='/'>Home</Link></li>
                <li><Link to='/users'>Users</Link></li>
                <li><Link to='/students'>Students</Link></li>
                <li><Link to='/register'>Register</Link></li>
            </ul>
        </nav>
    </header>
);

export default Header
