import ReactDOM from 'react-dom';
import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom'
import Libraries from './Libraries';
import DetailLibrary from './DetailLibrary';

const Example = (text) => {
    return(
        <>
            <BrowserRouter>
                <Routes>
                    <Route path='/library/:type' element={<Libraries text={text}/>} />
                    <Route path='/library/detail-library/:type/:id' element={<DetailLibrary text={text}/>} />
                </Routes>
            </BrowserRouter>
        </>
    )
}

export default Example

if (document.getElementById('react')) {
    const element = document.getElementById('languages')
    const text = Object.assign({}, element.dataset)
    ReactDOM.render(<Example {...text}/>, document.getElementById('react'));
} 
