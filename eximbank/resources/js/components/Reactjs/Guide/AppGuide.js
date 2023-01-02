import ReactDOM from 'react-dom';
import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom'
import Guide from './Guide';
import DetailGuidePost from './DetailGuidePost';

const AppGuide = (text) => {
    return(
        <>
            <BrowserRouter>
                <Routes>
                    <Route path='/guide-react/:type' element={<Guide text={text}/>} />
                    <Route path='/guide-react/detail-post/:id' element={<DetailGuidePost text={text}/>} />
                </Routes>
            </BrowserRouter>
        </>
    )
};

export default AppGuide;

if (document.getElementById('react')) {
    const element = document.getElementById('languages')
    const text = Object.assign({}, element.dataset)
    ReactDOM.render(<AppGuide {...text}/>, document.getElementById('react'));
} 