import React, { useState, useEffect } from 'react';
import { Link, useParams } from 'react-router-dom';    
import Axios from 'axios';
import { Input, DatePicker, Empty } from 'antd';
import {
    SearchOutlined  
} from '@ant-design/icons';

const Situation = ({text}) => {
    const { id } = useParams();
    const [data, setData] = useState([]);
    const [topic, setTopic] = useState('');
    const [loading, setLoading] = useState(true);
    const [dateFrom, setDateFrom] = useState('');
    const [search, setSearch] = useState('');

    const changeDateFrom = (date, dateString) => {
        setDateFrom(dateString);
    }

    const handleKeypress = (e) => {
        setLoading(true)
        setSearch(e.target.value) 
    }
    
    const likeSituation = async (id) => {
        try {
            const items = await Axios.post(`/user-like-situation/${id}`)
            .then((response) => {
                if (response.data.check_like == 1) {
                    $('#like_situation_'+id).html('<span class="color_blue"><i class="fas fa-thumbs-up"></i></span>');
                } else {
                    $('#like_situation_'+id).html('<i class="far fa-thumbs-up"></i>');
                }
                $('#view_like_'+id).html(response.data.view_like + ' Lượt thích');
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    useEffect(() => {
        const fetchDataItem = async () => {
            setLoading(true)
            try {
                const items = await Axios.get(`/data-situation/${id}?dateFrom=${dateFrom}&search=${search}`)
                .then((response) => {
                    setData(response.data.situations),
                    setTopic(response.data.topic),
                    setLoading(false)
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }
        
        fetchDataItem();
    }, [dateFrom, search]);

    return (
        <div className="container-fluid">
            <div className="row">
                <div className="col-md-12">
                    <div className="ibox-content forum-container">
                        <h2 className="st_title">
                            <a href="/">
                                <i className="uil uil-apps"></i>
                                <span>{text.home_page}</span>
                            </a>
                            <i className="uil uil-angle-right"></i>
                            <Link to={`/topic-situation-react`} className="font-weight-bold">{text.topic_situation}</Link>
                            <i className="uil uil-angle-right"></i>
                            <span className="font-weight-bold">{ topic.name }</span>
                        </h2>
                    </div>
                </div>
            </div>
            <div className="row m-0 search-course pb-2 my-2">
                <Input className="col-12 col-md-3 m-1" 
                    placeholder={text.enter_name_topic} 
                    prefix={<SearchOutlined />}
                    allowClear
                    onPressEnter={(e) => handleKeypress(e)} 
                />
                <DatePicker placeholder={text.date_created} className="col-12 col-md-2 m-1" onChange={changeDateFrom} />
            </div>
            <div className="row m-0">
                <div className="col-12 all_situations">
                { 
                    loading ? (
                        <div className='row'>
                            <div className="col-12 ajax-loading text-center mb-5">
                                <div className="spinner-border" role="status">
                                    <span className="sr-only">Loading...</span>
                                </div>
                            </div> 
                        </div>
                    ) : ( 
                    <>
                    {
                        data.length > 0 ? (
                        <>
                            {
                                data.map(item => (
                                    <div key={item.id} className="row wrapped_situation">
                                        <Link to={`/topic-situation-react/situation-detail/${topic.id}/${item.id}`} className="col-11">
                                            <div className="row">
                                                <div className="col-md-3 col-12 py-2 situation_name">
                                                    <span>{ item.name }</span>
                                                </div>
                                                <div className="col-md-3 col-12 py-2 situation_description">
                                                    <span>{ item.description }</span>
                                                </div>
                                                <div className="col-md-4 col-9 py-2 pr-0">
                                                    <ul className="comment_like_view">
                                                        <li>
                                                            <span>{ item.count_comment_situation } {text.comment}</span>
                                                        </li>
                                                        <li>
                                                            <span id={`view_like_${ item.id }`}>{ item.like } {text.likes}</span>
                                                        </li>
                                                        <li>
                                                            <span>{ item.view } <i className="fas fa-eye"></i></span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div className="col-md-2 col-3 py-2">
                                                    <span>{ item.created_at2 }</span>
                                                </div>
                                            </div>
                                        </Link>
                                        <div className="col-1 like_situation">
                                            <div className="like" id={`like_situation_${item.id }`} onClick={()=> likeSituation(item.id)}>
                                            {
                                                item.check_like == 1 ? (
                                                    <span className='color_blue'><i className="fas fa-thumbs-up"></i></span>
                                                ) : (
                                                    <span><i className="far fa-thumbs-up"></i></span>
                                                )
                                            }
                                            </div>
                                        </div>
                                    </div>
                                ))
                            }
                        </>
                        ) : (
                            <div className='mb-4'>
                                <Empty />
                            </div>
                        )
                    }
                    </>
                    )
                }
                </div>
            </div>
        </div>
    );
};

export default Situation;