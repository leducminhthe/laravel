
import ReactDOM from 'react-dom';
import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'
import Course from './Course';

const AppCourse = () => {
    return(
        <>
            <Router>
                <Routes>
                    <Route path='/course-react/:type' element={<Course />} />
                </Routes>
            </Router>
        </>
    )
}

export default AppCourse

if (document.getElementById('react')) {
    ReactDOM.render(<AppCourse />, document.getElementById('react'));
} 
