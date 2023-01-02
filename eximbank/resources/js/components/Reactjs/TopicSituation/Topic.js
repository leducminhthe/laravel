import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';    
import Axios from 'axios';
import InfiniteScroll from "react-infinite-scroll-component";
import { Input, DatePicker, Empty } from 'antd';
import {
    SearchOutlined  
} from '@ant-design/icons';

const Topic = ({text}) => {
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [dateFrom, setDateFrom] = useState('');
    const [dateTo, setDateTo] = useState('');
    const [search, setSearch] = useState('');
    const [page, setPage] = useState(2);
    const [hasMore, sethasMore] = useState(true);

    const changeDateFrom = (date, dateString) => {
        setDateFrom(dateString);
    }

    const changeDateTo = (date, dateString) => {
        setDateTo(dateString);
    }

    const handleKeypress = (e) => {
        setLoading(true)
        setSearch(e.target.value) 
    }

    useEffect(() => {
        const fetchDataItem = async () => {
            setLoading(true)
            try {
                const items = await Axios.get(`/data-topic?page=1&dateFrom=${dateFrom}&dateTo=${dateTo}&search=${search}`)
                .then((response) => {
                    setData(response.data.topics.data),
                    setLoading(false)
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }
        
        fetchDataItem();
    }, [dateFrom, dateTo, search]);

    const fetchDataScroll = async () => {
        const res = await Axios.get(`/data-topic?page=${page}&dateFrom=${dateFrom}&dateTo=${dateTo}&search=${search}`)
        return res;
    };

    const fetchData = async () => {
        const dataFormServer = await fetchDataScroll();
        setData([...data, ...dataFormServer.data.topics.data]);
        if (dataFormServer.data.topics.data.length === 0 || dataFormServer.data.topics.data.length < 6) {
          sethasMore(false);
        }
        setPage(page + 1);
    };

    return (
        <div className="container-fluid">
            <div className="row">
                <div className="col-12">
                    <div className="row">
                        <div className="col-md-12">
                            <div className="ibox-content forum-container">
                                <h2 className="st_title">
                                    <a href="/">
                                        <i className="uil uil-apps"></i>
                                        <span>{text.home_page}</span>
                                    </a>
                                    <i className="uil uil-angle-right"></i>
                                    <span className="font-weight-bold">{text.topic_situation}</span>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div className="row m-0 search-course my-2">
                        <Input className="col-12 col-md-3 m-1" 
                            placeholder={text.enter_name_topic} 
                            prefix={<SearchOutlined />}
                            allowClear
                            onPressEnter={(e) => handleKeypress(e)} 
                        />
                        <DatePicker className="col-12 col-md-2 m-1" onChange={changeDateFrom} />
                        <DatePicker className="col-12 col-md-2 m-1" onChange={changeDateTo} />
                    </div>
                    <div className="row mx-0 mb-3">
                        <div className="col-12">
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
                                        <InfiniteScroll className="row all_topic"
                                            dataLength={data.length}
                                            next={fetchData}
                                            hasMore={hasMore}
                                            style={{ overflow: 'unset'}}
                                        >
                                        <>
                                            {
                                                data.map(item => (
                                                    <div key={item.id} className="col-lg-3 col-md-4 p-1">
                                                        <div className="fcrse_1 my-3 p-0">
                                                            <Link to={`/topic-situation-react/situation/${item.id}`} className="image_topic_link">
                                                                <img className="picture_topic" src={item.image} alt="" height="" width="100%" />
                                                            </Link>
                                                            <div className="fcrse_content px-3">
                                                                <div className="course_names text-break">
                                                                    <Link to={`/topic-situation-react/situation/${item.id}`} className="crse14s topic_name">
                                                                        { item.name }
                                                                    </Link>
                                                                    <p className=""><i className='fa fa-calendar'></i> {item.created_at2}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                ))
                                            }
                                        </>
                                        </InfiniteScroll>
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
            </div>
    </div>
    );
};

export default Topic;