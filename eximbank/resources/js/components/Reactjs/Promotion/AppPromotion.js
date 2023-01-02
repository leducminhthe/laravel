import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'
import Promotion from './Promotion';
import UserMaxPoint from './UserMaxPoint';

const AppPromotion = (text) => {
    return (
        <>
            <Router>
                <Routes>
                    <Route path='/promotion-react' element={<Promotion text={text}/>} />
                    <Route path='/promotion-react/list-user-max-point' element={<UserMaxPoint text={text}/>} />
                </Routes>
            </Router>
        </>
    );
};

export default AppPromotion

if (document.getElementById('react')) {
    const element = document.getElementById('languages')
    const text = Object.assign({}, element.dataset)
    ReactDOM.render(<AppPromotion {...text}/>, document.getElementById('react'));
}
