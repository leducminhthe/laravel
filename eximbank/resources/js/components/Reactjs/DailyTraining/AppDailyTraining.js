
import ReactDOM from 'react-dom';
import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'
import DailyTraining from './DailyTraining';
import DetailDailyTraining from './DetailDailyTraining';
import CreateVideo from './CreateVideo';
import SearchVideo from './SearchVideo';

const AppDailyTraining = (text) => {
    return(
        <>
            <Router>
                <Routes>
                    <Route path='/daily-training-react/:type' element={<DailyTraining text={text}/> } />
                    <Route path='/daily-training-react/detail/:id' element={<DetailDailyTraining text={text}/>} />
                    <Route path='/daily-training-react/create-video' element={<CreateVideo text={text}/>} />
                    <Route path='/daily-training-react/search-video' element={<SearchVideo text={text}/>} />
                </Routes>
            </Router>
        </>
    )
}

export default AppDailyTraining

if (document.getElementById('react')) {
    const element = document.getElementById('languages')
    const text = Object.assign({}, element.dataset)
    ReactDOM.render(<AppDailyTraining {...text}/>, document.getElementById('react'));
} 
