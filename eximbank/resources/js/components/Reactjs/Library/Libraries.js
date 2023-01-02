import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';    
import Axios from 'axios';
import InfiniteScroll from "react-infinite-scroll-component";
import { Rate, Empty, Input, Select, Tooltip } from 'antd';
import {
    SearchOutlined,
    UserOutlined  
} from '@ant-design/icons';

const Libraries = ({ text }) => {
    const { type } = useParams();
    const [searchCate, setSearchCate] = useState(0);
    const [search, setSearch] = useState('');
    const [searchAuthor, setSearchAuthor] = useState('');
    const [status, setStatus] = useState('');
    const [cate, setCate] = useState([]);
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [cateName, setCateName] = useState([]);
    const [page, setPage] = useState(2);
    const [hasMore, sethasMore] = useState(true);
    const { Option } = Select;

    var nameType;
    if (type == 1) {
        nameType = text.book;
    } else if (type == 2) {
        nameType = text.ebook;
    } else if (type == 3) {
        nameType = text.document;
    } else if (type == 4) {
        nameType = "Video";
    } else {
        nameType = text.audiobook;
    }

    const desc = ['terrible', 'bad', 'normal', 'good', 'wonderful'];
    
    const selectHandel = (e) => {
        e ? setSearchCate(e) : setSearchCate('');
    }

    const selectHandelStatus = (e) => {
        e ? setStatus(e) : setStatus('');
    }

    const handleKeypress = (e) => {
        setLoading(true)
        setSearch(e.target.value) 
    }

    const handleKeypressAuthor = (e) => {
        setLoading(true)
        setSearchAuthor(e.target.value) 
    }

    const deleteAllSearch = () => {
        setSearch('');
        setSearchAuthor('');
        setSearchCate(0);
        setStatus('');
        setPage(2);
        setData([]);
        sethasMore(true);
        fetchDataItem();
    }

    const ratting = async (id,i) => {
        const ratting = await Axios.post(`/ratting-start-library/`,{ id, i })
        .then(() => {
            $('#rate_'+id).addClass("disabledbutton");
        })
    }
    
    const fetchDataItem = async () => {
        setLoading(true)
        try {
            const items = await Axios.get(`/get-libraries?page=1&searchAuthor=${searchAuthor}&searchCate=${searchCate}&search=${search}&status=${status}&type=${type}`)
            .then((response) => {
                setData(response.data.get_books.data),
                setCate(response.data.get_category_books),
                setCateName(response.data.all_name_cate_book),
                setLoading(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    useEffect(() => {
        fetchDataItem();
    }, [searchCate, search, searchAuthor, status, type]);

    const fetchDataScroll = async () => {
        const res = await Axios.get(`/get-libraries?page=${page}&searchAuthor=${searchAuthor}&searchCate=${searchCate}&search=${search}&status=${status}&type=${type}`)
        return res;
    };

    const fetchData = async () => {
        const dataFormServer = await fetchDataScroll();
        setData([...data, ...dataFormServer.data.get_books.data]);
        if (dataFormServer.data.get_books.data.length === 0 || dataFormServer.data.get_books.data.length.length < 6) {
          sethasMore(false);
        }
        setPage(page + 1);
    };

    return(
        <>
            <div className="container-fluid library_react">
                <div className="row">
                    <div className="col-md-12">
                        <div className="ibox-content forum-container">
                            <h2 className="st_title">
                                <a href="/">
                                    <i className="uil uil-apps"></i>
                                    <span>{text.home_page}</span>
                                </a>
                                <i className="uil uil-angle-right"></i>
                                <span className="font-weight-bold">{text.library}</span>
                                <i className="uil uil-angle-right"></i>
                                <span onClick={() => setSearchCate(0)} className="font-weight-bold span_link">
                                    {nameType}
                                </span>
                                {
                                    cateName.length > 0  ?
                                    cateName.slice().reverse().map(cate => (
                                        <span key={cate.id}>
                                            <i className="uil uil-angle-right"></i>
                                            <span onClick={() => setSearchCate(cate.id)} className="font-weight-bold span_link">
                                                {cate.name}
                                            </span>
                                        </span>
                                    )) : ''
                                }
                            </h2>
                        </div>
                    </div>
                    <div className="col-md-12">
                        <div className="row search-course pb-2">
                            <div className="col-md-12 mt-3 form-inline">
                                <form className="form-inline w-100" id="form-search">
                                    <Select showSearch
                                        className='w-100 col-12 col-md-3 mb-2'
                                        allowClear
                                        placeholder={text.category}
                                        onChange={selectHandel}
                                        filterOption={(input, option) =>
                                            option.children.toLowerCase().indexOf(input.toLowerCase()) >= 0
                                        }
                                    > 
                                    {
                                        cate.length > 0 && cate.map(cate => (
                                            <Option key={cate.id} value={cate.id}>{cate.name}</Option>
                                        ))
                                    }
                                    </Select>
                                    <div className="libraries_name col-12 col-md-2 mb-2">
                                        <Input placeholder={text.bookname} 
                                            className="w-100"
                                            prefix={<SearchOutlined />}
                                            allowClear
                                            onPressEnter={(e) => handleKeypress(e)} 
                                        />
                                    </div>
                                    <div className="authors col-12 col-md-2 mb-2">
                                        <Input placeholder={text.nameauthor} 
                                            className="w-100"
                                            prefix={<UserOutlined />}
                                            allowClear
                                            onPressEnter={(e) => handleKeypressAuthor(e)}
                                        />
                                    </div>
                                    {
                                        type == 1 && (
                                            <Select showSearch
                                                className='w-100 col-12 col-md-2 mb-2'
                                                allowClear
                                                placeholder={text.status}
                                                onChange={selectHandelStatus}
                                            > 
                                                <Option value={1}>{text.register}</Option>
                                                <Option value={2}>{text.approved}</Option>
                                                <Option value={3}>{text.borrow}</Option>
                                            </Select>
                                        )
                                    }
                                    
                                    <div className="col-12 col-md-2 delete_search mb-2">
                                        <button type="button" onClick={deleteAllSearch} className="btn">
                                            <i className="fa fa-trash"></i>&nbsp;
                                            {text.delete_search}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div className="_14d25 mb-5">
                            <div className="m-0" id="results">
                                { 
                                    loading ? (
                                        <div className="col-12 ajax-loading text-center mb-5">
                                            <div className="spinner-border" role="status">
                                                <span className="sr-only">Loading...</span>
                                            </div>
                                        </div> 
                                    ) : ( 
                                    <>
                                    {
                                        data.length > 0 ? (
                                            <InfiniteScroll className="row m-0"
                                                dataLength={data.length}
                                                next={fetchData}
                                                hasMore={hasMore}
                                                style={{ overflow: 'unset'}}
                                            >
                                            <>
                                                {
                                                    data.map(item => (
                                                        <div key={item.id} className="col-lg-2 col-md-3 p-1">
                                                            <div className="fcrse_1 library">
                                                                <Link to={`/library/detail-library/${type}/${item.id}`} className="fcrse_img mt-2">
                                                                    <img src={item.image} />
                                                                </Link>
                                                                <div className="fcrse_content">
                                                                    <div className="vdtodt">
                                                                        <span className="vdt14">
                                                                            { item.views }
                                                                            <i className="fa fa-eye ml-1"></i>
                                                                        </span>
                                                                        <span className="ml-2 vdt14">
                                                                            { item.time }
                                                                        </span>
                                                                    </div>
                                                                    <div className="div_name">
                                                                        <Link to={`/library/detail-library/${type}/${item.id}`} className="crse14s">
                                                                            <Tooltip placement="bottom" title={ item.name }>
                                                                                <span>{ item.name }</span>
                                                                            </Tooltip>
                                                                        </Link>
                                                                    </div>
                                                                    
                                                                    <div className="author">
                                                                        <span><i className="fas fa-user-circle"></i> { item.name_author }</span>
                                                                    </div>

                                                                    <div className="ratting_start_libraries">
                                                                        <div id={`rate_`+item.id}>
                                                                            {
                                                                                item.isRating ? (
                                                                                    <Rate className={`rating_star_`+item.id} 
                                                                                        disabled
                                                                                        value={item.isRating.ratting}
                                                                                    />
                                                                                ) : (
                                                                                    <Rate className={`rating_star_`+item.id} 
                                                                                        onChange={(e) => ratting(item.id, e)}
                                                                                        tooltips={desc}
                                                                                    />
                                                                                )
                                                                            }
                                                                        </div>
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
        </>
    )
}

export default Libraries
