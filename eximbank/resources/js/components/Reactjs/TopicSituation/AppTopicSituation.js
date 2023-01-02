import ReactDOM from 'react-dom';
import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'
import Topic from './Topic';
import Situation from './Situation';
import SituationDetail from './SituationDetail';

const AppTopicSituation = (text) => {
    return(
        <>
            <Router>
                <Routes>
                    <Route path='/topic-situation-react' element={<Topic text={text}/>} />
                    <Route path='/topic-situation-react/situation/:id' element={<Situation text={text}/>} />
                    <Route path='/topic-situation-react/situation-detail/:topic_id/:id' element={<SituationDetail text={text}/>} />
                </Routes>
            </Router>
        </>
    )
}

export default AppTopicSituation

if (document.getElementById('react')) {
    const element = document.getElementById('languages')
    const text = Object.assign({}, element.dataset)
    ReactDOM.render(<AppTopicSituation {...text}/>, document.getElementById('react'));
} 
