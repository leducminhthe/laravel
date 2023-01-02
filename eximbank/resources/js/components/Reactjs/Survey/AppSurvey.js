import ReactDOM from 'react-dom';
import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'
import Survey from './Survey';
import SurveyUser from './SurveyUser';
import EditSurveyUser from './EditSurveyUser';
import SurveyRealTime from './SurveyRealTime';

const AppSurvey = (text) => {
    return(
        <>
            <Router>
                <Routes>
                    <Route path='/survey-react' element={<Survey text={text}/>} />
                    <Route path='/survey-react/user/:id' element={<SurveyUser text={text}/>} />
                    <Route path='/survey-react/online/:id/:type' element={<SurveyRealTime text={text}/>} />
                    <Route path='/survey-react/edit-user/:id' element={<EditSurveyUser text={text}/>} />
                    <Route path='/survey-react/edit-user-online/:id/:type' element={<SurveyRealTime text={text}/>} />
                </Routes>
            </Router>
        </>
    )
}

export default AppSurvey

if (document.getElementById('survey')) {
    const element = document.getElementById('languages')
    const text = Object.assign({}, element.dataset)
    ReactDOM.render(<AppSurvey {...text}/>, document.getElementById('survey'));
    
} 
