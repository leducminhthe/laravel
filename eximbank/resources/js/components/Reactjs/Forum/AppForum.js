import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'
import Forums from './Forums';
import Topic from './Topic';
import Thread from './Thread';
import EditThread from './EditThread';
import CreateThread from './CreateThread';

const AppForum = (text) => {
    return (
        <>
            <Router>
                <Routes>
                    <Route path='/forums-react' element={<Forums text={text}/>} />
                    <Route path='/forums-react/topic/:id' element={<Topic text={text}/>} />
                    <Route path='/forums-react/thread/:id' element={<Thread text={text}/>} />
                    <Route path='/forums-react/edit-thread/:id' element={<EditThread text={text}/>} />
                    <Route path='/forums-react/create-thread/:topic_id' element={<CreateThread text={text}/>} />
                </Routes>
            </Router>
        </>
    );
};

export default AppForum;

if (document.getElementById('react')) {
    const element = document.getElementById('languages')
    const text = Object.assign({}, element.dataset)
    ReactDOM.render(<AppForum {...text}/>, document.getElementById('react'));
} 