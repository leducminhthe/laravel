import React, { useState, useEffect } from 'react';
import { Link, useParams } from 'react-router-dom';    
import Axios from 'axios';
import NewsRight from './NewsRight';
import RelatedNews from './RelatedNews';
import { Tooltip, Button } from 'antd';
import {
    LeftOutlined,
    RightOutlined
} from '@ant-design/icons';

const CateNews = ({ newsRight, text }) => {
    const { type } = useParams();
    const { cate_id } = useParams();
    const [allCateNames, setAllCateNames] = useState([]);
    const [cateNames, setCateNames] = useState('');
    const [hotNewsCate, setHotNewsCate] = useState('');
    const [relatedHotNewsCate, setRelatedHotNewsCate] = useState([]);
    const [newsId, setNewsId] = useState([]);
    const [loading, setLoading] = useState(true);
    const color = '#2ecffc';
    
    const fetchDataCateNew = async () => {
        try {
            const items = await Axios.get(`/cate-news-name/${cate_id}/${type}`)
            .then((response) => {
                setAllCateNames(response.data.all_cate_name),
                setCateNames(response.data.cate_new),
                runFun()
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }
        
    useEffect(() => {
        const fetchDataItem = async () => {
            setLoading(true)
            try {
                const items = await Axios.get(`/data-cate-news/${cate_id}/${type}`)
                .then((response) => {
                    setHotNewsCate(response.data.get_hot_new_of_category),
                    setRelatedHotNewsCate(response.data.get_related_news_hot),
                    setNewsId(response.data.news_id),
                    setLoading(false)
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }
        fetchDataCateNew()
        fetchDataItem();
    }, [cate_id]);

    const scrollRight = () => {
        document.getElementById("slide_menu").scrollBy(150, 0);
    }
    const scrollLeft = () => {
        document.getElementById("slide_menu").scrollBy(-150, 0);
    }

    const runFun = () => {
        var widthCateChild = document.getElementsByClassName("all_cate_news")[0].clientWidth;
        var widthId = document.getElementById("slide_menu").scrollWidth;
        if (widthId > widthCateChild) {
            $('.left_click').show();
            $('.right_click').show();
        }
    }

    return (
        <div className="body_news row mx-0 my-3 pb-4">
            <div className="col-12 cate_parent_name">
                <h3 className="mb-3">
                    <Link to={`/news-react/cate-new/${cateNames.id}/0`}>
                        <span>{ cateNames.name }</span>
                    </Link>

                </h3>
            </div>
            <div className="col-12">
                <div className="all_cate_news">
                    <div className='left_click' >
                        <Button type="primary" icon={<LeftOutlined />} size={'small'} onClick={(e) => scrollLeft()}></Button>
                    </div>
                    <div className="scrollmenu" id='slide_menu'>
                        {
                            allCateNames.map((allCateName) => (
                                <Link key={allCateName.id} className="link_cate_new" to={`/news-react/cate-new/${allCateName.id}/1`}>
                                    <span className={ allCateName.id == cate_id ? `span_access` : ''}>
                                        { allCateName.name }
                                    </span>
                                </Link>
                            ))
                        }
                    </div>
                    <div className='right_click' >
                        <Button type="primary" icon={<RightOutlined />} size={'small'} onClick={(e) => scrollRight()}></Button>
                    </div>
                </div>
            </div>
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
                        <div className="row new_with_category mb-3">
                            <div className="col-12">
                                <div className="row mb-2 get_new">
                                {
                                    hotNewsCate && (
                                    <>
                                        <div className="col-5 p-0">
                                            <div className='wrapped_new'>
                                                <Link to={`/news-react/detail/${hotNewsCate.id}`}>
                                                    <img className="w-100" height="auto" src={ hotNewsCate.image } alt=""/>
                                                </Link>
                                            </div>
                                        </div>
                                        <div className="col-7 pt-2 hot_new">
                                            <div className="hot_new_title">
                                                <Link to={`/news-react/detail/${hotNewsCate.id}`}>
                                                    <h4 className="mb-2">
                                                        <Tooltip placement="bottom" color={color} title={ hotNewsCate.title }>
                                                            <strong>{ hotNewsCate.title }</strong>
                                                        </Tooltip>
                                                    {
                                                        hotNewsCate.checkDate && (
                                                            <img className="icon_new" src={ hotNewsCate.checkDate } alt="" width="45px"/>
                                                        )
                                                    }
                                                    </h4>
                                                </Link>
                                            </div>
                                            <div className="created_at mb-2">
                                                { hotNewsCate.created_at2 }
                                            </div>
                                            <div className="hot_new_description">
                                                { hotNewsCate.description }
                                            </div>
                                        </div>
                                    </>
                                    )
                                }
                                </div>
                            </div>
                            <div className="col-12 related_news_hot_cate mt-2">
                                <div className="row m-0">
                                {
                                    relatedHotNewsCate.map((relatedHotNewCate) => (
                                        <div key={relatedHotNewCate.id} className={`col-4 related_new_hot px-2 ${relatedHotNewCate.check_user_like == 1 ? 'user_like_new' : ''}`}>
                                            <Link className="link_related_new_hot" to={`/news-react/detail/${relatedHotNewCate.id}`}>
                                                <h4 className="title_related_new_hot">
                                                    <Tooltip placement="bottom" color={color} title={ relatedHotNewCate.title }>
                                                        <strong className='title_new_right'>
                                                            { relatedHotNewCate.title }
                                                        </strong>
                                                    </Tooltip>
                                                {/* {
                                                    relatedHotNewCate.checkDate && (
                                                        <img className="icon_new" src={ relatedHotNewCate.checkDate } alt="" width="45px"/>
                                                    )
                                                } */}
                                                </h4>
                                            </Link>
                                            <div className="hot_new_description">
                                                { relatedHotNewCate.description }
                                            </div>
                                        </div>
                                    ))
                                }
                                </div>
                            </div>
                        </div>

                        <div className="mt-2 news_category">
                            <h4 className='all_realated_new mt-1'>
                                <strong>
                                    <span>{text.related_news}</span>
                                </strong>
                            </h4>
                            <RelatedNews cateId={cate_id}  id={0} search={newsId} type={0}/>
                        </div>
                    </>
                )
            }
            </div>
            <NewsRight loading={loading} newsRight={newsRight} text={text}/>
        </div>
    );
};

export default CateNews;