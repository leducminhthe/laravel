import React, { useState, useEffect } from 'react';
import { Link, useLocation } from 'react-router-dom';    
import Axios from 'axios';
import NewsRight from './NewsRight';
import { Tooltip, Pagination } from 'antd';
import {
    EyeOutlined,
    LikeOutlined
} from '@ant-design/icons';

const NewsViewLike = ({ newsRight, text }) => {
    const location = useLocation();
    const type = location.state.type;
    const [page, setPage] = useState(1);
    const [total, setTotal] = useState('');
    const [perPage, setperPage] = useState('');
    const [loading, setLoading] = useState(true);
    const color = '#2ecffc';
    const [news, setNews] = useState([]);

    const changePage = (page) => {
        setPage(page);
    };

    useEffect(() => {
        const fetchDataItem = async () => {
            setLoading(true)
            try {
                const items = await Axios.get(`/data-new-view-like/${type}?page=${page}`)
                .then((response) => {
                    setNews(response.data.news.data),
                    setTotal(response.data.news.total),
                    setperPage(response.data.news.per_page),
                    setLoading(false)
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }
        fetchDataItem();
    }, [type, page]);

    return (
        <div className="body_news_view_like row mx-0 my-3 pb-4">
            <div className="content_left col-md-8 col-12 mt-2">
            {
                loading ? (
                    <div className="col-12 ajax-loading text-center m-5">
                        <div className="spinner-border" role="status">
                            <span className="sr-only">Loading...</span>
                        </div>
                    </div> 
                ) : (
                    <>
                        <div className="row mb-3">
                            <div className="col-12">
                                <h3 className='title_type mt-1'>
                                    <strong>
                                        {
                                            type == 1 ? (
                                                <span>{text.most_view_post}</span>
                                            ) : (
                                                <span>{text.most_liked_post}</span>
                                            )
                                        }
                                    </strong>
                                </h3>
                            </div>
                            <div className="col-12">
                                {
                                    news.map((newDetail) => (
                                        <div key={newDetail.id} className="row my-3 get_news_category">
                                            <div className="col-5 col-md-4 pr-0">
                                                <Link to={`/news-react/detail/${newDetail.id}`}>
                                                    <img className="w-100" src={ newDetail.image } alt="" height="auto"/>
                                                </Link>
                                            </div>
                                            <div className={`col-7 col-md-8 new_category ${newDetail.check_user_like == 1 ? 'user_like_new' : ''}`}>
                                                <div className="news_category_title">
                                                    <Link to={`/news-react/detail/${newDetail.id}`}>
                                                        <p className='mb-1 time_view_get_news_category'>
                                                            <span>{ newDetail.created_at2 }  { newDetail.views } <EyeOutlined className='mx-1'/>  { newDetail.like_new } <LikeOutlined className='ml-1'/>
                                                            {
                                                                newDetail.checkDate && (
                                                                    <img className="icon_new" src={ newDetail.checkDate } alt="" width="45px"/>
                                                                )
                                                            }
                                                            </span>
                                                        </p>
                                                        <h4 className="mb-2 name_title_related_new">
                                                            <Tooltip placement="bottom" color={color} title={ newDetail.title }>
                                                                { newDetail.title }
                                                            </Tooltip>
                                                        </h4>
                                                    </Link>
                                                </div>
                                                <div className="news_category_description">
                                                    { newDetail.description }
                                                </div>                                    
                                            </div>
                                    </div>
                                    ))
                                }
                            </div>
                            <div className="col-12">
                                <Pagination onChange={changePage} defaultCurrent={page} total={total} pageSize={perPage}/>
                            </div>
                        </div>
                    </>
                )
            }
            </div>
            <NewsRight loading={loading} newsRight={newsRight} text={text}/>
        </div>
    );
};

export default NewsViewLike;