import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';    
import Axios from 'axios';
import InfiniteScroll from "react-infinite-scroll-component";
import {
    EyeOutlined,
    LikeOutlined
} from '@ant-design/icons';
import { Tooltip, Pagination } from 'antd';

const RelatedNews = ({ cateId, id, search, type }) => {
    const [relatedNews, setRelatedNews] = useState([]);
    const [page, setPage] = useState(1);
    const [total, setTotal] = useState('');
    const [perPage, setperPage] = useState('');
    const [loading, setLoading] = useState(true);
    const color = '#2ecffc';

    const changePage = (page) => {
        setPage(page);
    };

    useEffect(() => {
        const fetchDataItem = async () => {
            setLoading(true)
            try {
                const items = await Axios.get(`/related-new/${cateId}/${id}?page=${page}`, { search, type })
                .then((response) => {
                    setRelatedNews(response.data.get_related_news.data),
                    setTotal(response.data.get_related_news.total),
                    setperPage(response.data.get_related_news.per_page),
                    setLoading(false)
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }
        
        fetchDataItem();
    }, [search, page]);
    
    return (
        <>
        {
            loading ? (
                <div className='row m-4'>
                    <div className="col-12 ajax-loading text-center m-3">
                        <div className="spinner-border" role="status">
                            <span className="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            ) : (
                <div className='wrraped_related_new'>
                    {
                        relatedNews.map((relatedNew) => (
                            <div key={relatedNew.id} className="row my-3 get_news_category">
                                <div className="col-5 col-md-4 pr-0">
                                    <div className='wrapped_new'>
                                        <Link to={`/news-react/detail/${relatedNew.id}`}>
                                            <img className="w-100" src={ relatedNew.image } alt="" height="auto"/>
                                        </Link>
                                    </div>
                                </div>
                                <div className="col-7 col-md-8 new_category">
                                    <div className="news_category_title">
                                        <Link to={`/news-react/detail/${relatedNew.id}`}>
                                            <p className='mb-1 time_view_get_news_category'>
                                                <span>
                                                    { relatedNew.created_at2 }  
                                                    <span className='ml-2'>{ relatedNew.views } <EyeOutlined className='ml-1'/></span>
                                                    <span className='ml-2'>
                                                        { relatedNew.like_new } 
                                                        {
                                                            relatedNew.check_user_like ? (
                                                                <img src={ relatedNew.check_user_like } className='ml-1' width="16px" />
                                                            ) : (
                                                                <LikeOutlined className='ml-1'/>
                                                            )
                                                        }
                                                    </span>
                                                    {
                                                        relatedNew.checkDate && (
                                                            <img className="icon_new" src={ relatedNew.checkDate } alt="" width="45px"/>
                                                        )
                                                    }
                                                </span>
                                            </p>
                                            <h5 className="mb-2 name_title_related_new">
                                                <Tooltip placement="bottom" color={color} title={ relatedNew.title }>
                                                    <strong>{ relatedNew.title }</strong>
                                                </Tooltip>
                                            </h5>
                                        </Link>
                                    </div>
                                    <div className="news_category_description">
                                        { relatedNew.description }
                                    </div>                                    
                                </div>
                        </div>
                        ))
                    }
                    <div className="row">
                        <div className="col-12">
                            <Pagination onChange={changePage} defaultCurrent={page} total={total} pageSize={perPage}/>
                        </div>
                    </div>
                </div>
            )
        }
        </>
    );
};

export default RelatedNews;