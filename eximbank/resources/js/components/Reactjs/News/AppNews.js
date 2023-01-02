
import ReactDOM from 'react-dom';
import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'
import News from './News';
import MenuNews from './Menu/MenuNews';
import DetailNew from './DetailNew';
import CateNews from './CateNews';
import Axios from 'axios';
import NewsViewLike from './NewsViewLike';

const AppNews = ({icon, text}) => {
    const [newsRight, setNewsRight] = useState([]);
    
    const fetchDataItem = async () => {
        try {
            const items = await Axios.get(`/data-news-right`)
            .then((response) => {
                setNewsRight(response.data.get_news_category_sort_right)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    useEffect(() => {
        fetchDataItem();
        // fetchDataAdverstingPhoto();
    }, []);

    return(
        <Router>
            <div className="container-fluid">
                <MenuNews icon={icon}/>
                <Routes>
                    <Route path='/news-react' element={<News newsRight={newsRight} text={text}/>} />
                    <Route path='/news-react/cate-new/:cate_id/:type' element={<CateNews newsRight={newsRight} text={text}/>} />
                    <Route path='/news-react/detail/:id' element={<DetailNew newsRight={newsRight} text={text}/>} />
                    <Route path='/news-react/news-view-like' element={<NewsViewLike newsRight={newsRight} text={text}/>} />
                </Routes>
            </div>
        </Router>
    )
}

export default AppNews

if (document.getElementById('react')) {
    const element = document.getElementById('icon_news');
    const icon = Object.assign({}, element.dataset);
    const elementText = document.getElementById('languages')
    const text = Object.assign({}, elementText.dataset)
    ReactDOM.render(<AppNews icon={icon} text={text}/>, document.getElementById('react'));
} 
