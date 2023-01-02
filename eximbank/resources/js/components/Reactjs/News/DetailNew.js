import React, { useState, useEffect } from 'react';
import { Link, useParams } from 'react-router-dom';    
import Axios from 'axios';
import { Image, DatePicker } from 'antd';
import NewsRight from './NewsRight';
import RelatedNews from './RelatedNews';

const DetailNew = ({ newsRight, text }) => {
    const { id } = useParams();
    const [detail, setDetail] = useState('');
    const [detailsLink, setDetailsLink] = useState([]);
    const [loading, setLoading] = useState(true);
    const [searchDate, setSearchDate] = useState('');

    const likeNew = async (id) => {
        document.querySelector('#like_new').style.pointerEvents = 'none';
        try {
            const items = await Axios.post(`/user-like-new`, { id })
            .then((response) => {
                if (response.data.check_like == 1) {
                    $('#like_new').html('<span><i class="fas fa-check"></i> Thích '+ response.data.view_like + '</span>');
                } else {
                    $('#like_new').html('<span><i class="far fa-thumbs-up"></i> Thích ' + response.data.view_like + '</span>');
                }
                document.querySelector('#like_new').style.pointerEvents = 'auto';
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const changeSearchDate = (date, dateString) => {
        setSearchDate(dateString);
    }

    useEffect(() => {
        const fetchDataItem = async () => {
            setLoading(true)
            try {
                const items = await Axios.get(`/data-detail-new/${id}`)
                .then((response) => {
                    setDetail(response.data.get_new),
                    setDetailsLink(response.data.news_links)
                    setLoading(false)
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }
        
        fetchDataItem();
    }, [id]);

    return (
        <div className="row m-0 my-2 pt-2 bg-white pb-4">
            <div className="content_left col-md-8 col-12 body_new_detail">
            {
                loading ? (
                    <div className='row m-5'>
                        <div className="col-12 ajax-loading text-center mb-5">
                            <div className="spinner-border" role="status">
                                <span className="sr-only">Loading...</span>
                            </div>
                        </div> 
                    </div>
                ) : (
                <>
                    <div className="breadcum row mb-3">
                        <div className="col-md-9 col-12 pr-1">
                            <Link to={`/news-react/cate-new/${ detail.categoryParnetNew.id }/0`}>
                                <span className="title_cate_parent mr-2">{ detail.categoryParnetNew.name } </span>
                            </Link>
                            <i className="fa fa-angle-right mr-2" aria-hidden="true"></i>
                            <Link to={`/news-react/cate-new/${detail.category_new.id}/1`}>
                                { detail.category_new.name }
                            </Link>
                        </div>
                        <div className="col-md-3 col-12 date_now p-1">
                            { detail.dt }
                        </div>
                    </div>
                    <div className="new_detail">
                        <h3><strong>{ detail.title }</strong>
                        {
                            detail.checkDate && (
                                <img className="icon_new" src={ detail.checkDate } alt="" width="45px"/>
                            )
                        }
                        </h3>
                        <div className="row">
                            <div className="date_category col-md-9 col-12">
                                <span>{text.date_submit}: { detail.created_at2 }</span> |
                                <span><strong>{ detail.category_new.name }</strong></span>
                            </div>
                            <div className="like col-md-3 col-12">
                                <span onClick={() => likeNew(detail.id)} id="like_new">
                                {
                                    detail.checkLike == 1 ? (
                                        <span>
                                            <i className="fas fa-check mr-1"></i>
                                            {text.like} {detail.like_new}
                                        </span>
                                    ) : (
                                        <span>
                                            <i className="far fa-thumbs-up mr-1"></i>
                                            {text.like} {detail.like_new}
                                        </span>
                                    )
                                }
                                </span>
                            </div>
                        </div>
                        <div className="new_detail_description mt-3 news-content">
                            {(() => {
                                if (detail.type == 2) {
                                    return (
                                        <div className="mt-2">
                                            <center>
                                                <video width="100%" height="auto" controls>
                                                    <source src={ detail.content } type="video/mp4"/>
                                                </video>
                                            </center>
                                        </div>
                                    )
                                } else if (detail.type == 3) {
                                    return (
                                        <Image.PreviewGroup>
                                            <div className='row'>
                                            {
                                                detail.content.map((picture, index) => (
                                                    <div key={picture.id} className="col-md-4 col-12 mt-2">
                                                        <Image className="image_details" height={160} src={ picture } />
                                                    </div>
                                                ))
                                            }
                                            </div>
                                        </Image.PreviewGroup>
                                    )
                                } else {
                                    return (
                                        <div className="content_description_new" dangerouslySetInnerHTML={{ __html: detail.content }}>
                                        </div>
                                    )
                                }
                            })()}
                            <div className="mt-2">
                            {
                                detailsLink.map((detailLink) => (
                                    <div key={detailLink.id}>
                                    {
                                        detailLink.type == 'file' ? (
                                        <>
                                            {
                                                <a href={ detailLink.checkLink } target="_blank" className="mb-2 link_detail">
                                                    {
                                                        detailLink.checkFilePdf == 1 ? (
                                                            <i className="fa fa-eye mr-1" aria-hidden="true"></i>
                                                        ) : (
                                                            <i className="fa fa-download mr-1" aria-hidden="true"></i>
                                                        )
                                                    }
                                                    <span>{ detailLink.titleName }</span>
                                                </a>
                                            }
                                        </>
                                        ) : (
                                            <a href={ detailLink.link } target="_blank" className="link_detail mb-2">
                                                <i className="fa fa-link" aria-hidden="true"></i>
                                                { detailLink.titleName }
                                            </a>
                                        )
                                    }
                                    </div>
                                ))
                            }
                            </div>
                        </div>
                    </div>

                    <div className="row mt-3 return">
                        <div className="col-12">
                            <Link to={`/news-react/cate-new/${ detail.category_new.id }/1`}>
                                <button type="button" className="btn btn-light button_return">
                                    <i className="fa fa-angle-left" aria-hidden="true"></i>
                                </button>
                            </Link>
                        </div>
                    </div>

                    <div className="related_new" id="related_new">
                        <div className="row mb-3">
                            <div className="col-md-5 col-12">
                                <h4 className='title_related_new'>
                                    <span>{text.related_news}</span>
                                </h4>
                            </div>
                            <div className="search col-md-7 col-12">
                                <label>{text.view_by_date}</label>
                                <div className="date_search">
                                    <DatePicker className="m-1" onChange={changeSearchDate} />
                                </div>
                            </div>
                        </div>
                        <RelatedNews cateId={detail.category_new.id}  id={detail.id} search={searchDate} type={1}/>
                    </div>
                </>
                )
            }    
            </div>
            <NewsRight loading={loading} newsRight={newsRight} text={text}/>
        </div>
    );
};

export default DetailNew;