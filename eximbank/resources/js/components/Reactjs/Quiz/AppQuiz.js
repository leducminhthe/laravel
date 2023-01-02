
import ReactDOM from 'react-dom';
import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'
import Quiz from './Quiz';

const AppQuiz = (text) => {
    return(
        <>
            <Router>
                <Routes>
                    <Route path='/quiz-react' element={<Quiz text={text}/>} />
                </Routes>
            </Router>
        </>
    )
}

export default AppQuiz

if (document.getElementById('react')) {
    const element = document.getElementById('languages')
    const text = Object.assign({}, element.dataset)
    ReactDOM.render(<AppQuiz {...text}/>, document.getElementById('react'));
} 
