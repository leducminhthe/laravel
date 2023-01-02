import ReactDOM from 'react-dom';
import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'
import Suggest from './Suggest';
import CommentSuggest from './CommentSuggest';

const AppSuggest = (text) => {
    return(
        <>
            <Router>
                <Routes>
                    <Route path='/suggest-react' element={<Suggest text={text}/>} />
                    <Route path='/suggest-react/comment-suggest/:id' element={<CommentSuggest text={text}/>} />
                </Routes>
            </Router>
        </>
    )
}

export default AppSuggest

if (document.getElementById('react')) {
    const element = document.getElementById('languages')
    const text = Object.assign({}, element.dataset)
    ReactDOM.render(<AppSuggest {...text}/>, document.getElementById('react'));
} 
