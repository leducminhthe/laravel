import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import Axios from 'axios';
import { Input } from 'antd';
import { Popconfirm, message } from 'antd';
import {
    SearchOutlined
} from '@ant-design/icons';

const Forums = ({text}) => {
    let navigate = useNavigate();
    const [search, setSearch] = useState("");
    const [dataForums, setDataForums] = useState([]);
    const [checkAdmin, setCheckAdmin] = useState('');
    const [loading, setLoading] = useState(true);

    const handleKeypress = (e) => {
        setLoading(true)
        setSearch(e.target.value)
    }

    const handleDeleteThread = async (thread_id) => {
        try {
            const items = await Axios.post(`/remove-thread/${thread_id}`)
            .then((response) => {
                if (response.data.status == 'success') {
                    message.success('Xóa thành công');
                    fetchDataItem();
                }
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const handleEdit = (thread_id) => {
        navigate(`/forums-react/edit-thread/${thread_id}`);
    }

    const fetchDataItem = async () => {
        setLoading(true)
        try {
            const items = await Axios.get(`/data-forums?search=${search}`)
            .then((response) => {
                setDataForums(response.data.data_forums),
                setCheckAdmin(response.data.is_admin),
                setLoading(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    useEffect(() => {
        fetchDataItem();
    }, [search]);

    return (
        <div className="container-fluid" id="forums-react">
            <div className="row">
                <div className="col-md-12">
                    <div className="ibox-content forum-container">
                        <h2 className="st_title">
                            <a href="/">
                                <i className="uil uil-apps"></i>
                                <span>{text.home_page}</span>
                            </a>
                            <i className="uil uil-angle-right"></i>
                           <span className="font-weight-bold"> {text.forum}</span>
                        </h2>
                    </div>
                </div>
            </div>
            <div className="row">
                <div className="col-12 my-3" >
                    <Input
                        placeholder={text.enter_hashtag}
                        prefix={<SearchOutlined />}
                        allowClear
                        onPressEnter={(e) => handleKeypress(e)}
                    />
                </div>
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
                        search.length > 0 ? (
                            <div className="row m-0">
                                <div className="col-12 bg-white">
                                    <div className="forum-sub-title">
                                    {
                                        dataForums.map((thread) => (
                                            <div key={thread.id} className="forum-item border-bottom">
                                                <div className="row">
                                                    <div className="col-md-7">
                                                        <div className="forum-avatar">
                                                            <img className="img-circle" src={ thread.profileAvatar }
                                                                data-toggle="tooltip"
                                                                data-placement="top"/>
                                                        </div>
                                                        {
                                                            (checkAdmin || thread.checkUpdatedBy == 1) && (
                                                                <div className="eps_dots more_dropdown tool">
                                                                    <a href=""><i className="uil uil-ellipsis-v"></i></a>
                                                                    <div className="dropdown-content">
                                                                        <span onClick={() => handleEdit(thread.id)}><i className="uil uil-clock-three"></i>{text.edit}</span>
                                                                        <span className="remove-item" data-id={ thread.id }><i className="uil uil-ban"></i>{text.delete}</span>
                                                                    </div>
                                                                </div>
                                                            )
                                                        }
                                                        <Link to=''
                                                        data-toggle="tooltip"
                                                        data-placement="bottom"
                                                        className="forum-item-title">
                                                            { thread.title }
                                                        </Link>
                                                        <div className="forum-sub-title"><i>{ thread.created_at2 }</i> { thread.profileName }</div>
                                                        <div className={`forum-sub-title ` + (search == thread.hashtag ? 'text-primary' : '') }>
                                                            { thread.hashtag }
                                                        </div>
                                                        <div className="forum-sub-title">{ thread.content }</div>
                                                    </div>
                                                    <div className="col-md-1 forum-info">
                                                        <span className="views-number">
                                                            { thread.views }
                                                        </span>
                                                        <div>
                                                            <small>{text.view}</small>
                                                        </div>
                                                    </div>
                                                    <div className="col-md-1 forum-info">
                                                    {
                                                        (checkAdmin || thread.checkUpdatedBy == 1) && (
                                                            <div className="eps_dots more_dropdown">
                                                                <a href="#"><i className="uil uil-ellipsis-v"></i></a>
                                                                <div className="dropdown-content">
                                                                    <span onClick={() => handleEdit(thread.id)} ><i className="uil uil-clock-three"></i>{text.edit}</span>
                                                                    <span className="remove-item" data-id={ thread.id }><i className="uil uil-ban"></i>{text.delete}</span>
                                                                </div>
                                                            </div>
                                                        )
                                                    }
                                                        <span className="views-number">
                                                            { thread.count_comment }
                                                        </span>
                                                        <div>
                                                            <small>{text.comment}</small>
                                                        </div>
                                                    </div>
                                                    <div className="col-md-3 forum-comment-box">
                                                    {
                                                        thread.lastComment ? (
                                                            <div>
                                                                <p className="forum-avatar-box">
                                                                    <a className="forum-avatar">
                                                                        <img className="img-comment" src={ thread.lastCommentAvatar }
                                                                            data-toggle="tooltip"
                                                                            data-placement="top" />
                                                                    </a>
                                                                    <Link to="{{route('module.frontend.forums.thread',['id' => thread.id])}}"
                                                                    data-toggle="tooltip"
                                                                    data-placement="bottom"
                                                                    className="forum-permalink">
                                                                        { thread.lastCommentCreated }
                                                                    </Link>
                                                                </p>
                                                                <div className="forum-comment">{ thread.lastCommentContent }</div>
                                                            </div>
                                                        ) : (
                                                            <div>
                                                                <p className="forum-avatar-box">
                                                                    <a className="forum-avatar">
                                                                        <img className="img-comment" src={ thread.profileAvatar }
                                                                            data-toggle="tooltip"
                                                                            data-placement="bottom" />
                                                                    </a>
                                                                    <Link to="{{route('module.frontend.forums.thread',['id' => thread.id])}}"
                                                                    data-toggle="tooltip"
                                                                    data-placement="bottom"
                                                                    className="forum-permalink">
                                                                        { thread.dateCreated }
                                                                    </Link>
                                                                </p>
                                                                <div className="forum-comment">{ thread.content }</div>
                                                            </div>
                                                        )
                                                    }
                                                    </div>
                                                </div>
                                            </div>
                                        ))
                                    }
                                    </div>
                                </div>
                            </div>
                        ) : (
                            <div className="ibox-content forum-container">
                            {
                                dataForums.map((forumCategory) => (
                                <div key={forumCategory.id}>
                                {
                                    forumCategory.checked == 0 && (
                                        <div>
                                            <div className="forum-title opts_account pl-0">
                                                <h3>
                                                    <img src={ forumCategory.icon } alt="" className="ml-0"/>  { forumCategory.name }
                                                </h3>
                                            </div>
                                            {
                                                forumCategory.forums.map((item) => (
                                                    <div key={item.id} className="forum-item active border-bottom">
                                                        <div className="row">
                                                            <div className="col-md-9">
                                                                <div className="forum-icon pl-2 ml-3 ">
                                                                    <img src={ item.icon2 } alt="" className="" width={'32px'} height={'32px'} />
                                                                </div>
                                                                <Link to={`/forums-react/topic/${item.id}`} className="forum-item-title">{ item.name }</Link>
                                                            </div>
                                                            <div className="col-md-1 forum-info">
                                                                <span className="views-number">
                                                                    { item.getTotalView }
                                                                </span>
                                                                <div>
                                                                    <small>{text.view}</small>
                                                                </div>
                                                            </div>
                                                            <div className="col-md-1 forum-info">
                                                                <span className="views-number">
                                                                    { item.threadCount }
                                                                </span>
                                                                <div>
                                                                    <small>{text.posts}</small>
                                                                </div>
                                                            </div>
                                                            <div className="col-md-1 forum-info">
                                                                <span className="views-number">
                                                                    { item.getTotalComment }
                                                                </span>
                                                                <div>
                                                                    <small>{text.comment}</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div className="row">
                                                            <div className="col-12">
                                                                <div className="forum-sub-title">
                                                                    {
                                                                        item.thread.map((thread) => (
                                                                            <div key={thread.id} className="forum-item border-0">
                                                                                <div className="row">
                                                                                    <div className="col-md-7">
                                                                                        <div className="forum-avatar">
                                                                                            <img className="img-circle" src={ thread.profileAvatar }
                                                                                                data-toggle="tooltip"
                                                                                                data-placement="top"/>
                                                                                        </div>
                                                                                        {
                                                                                            checkAdmin || thread.checkUpdatedBy == 1 && (
                                                                                                <div className="eps_dots more_dropdown tool">
                                                                                                    <a href="#"><i className="uil uil-ellipsis-v"></i></a>
                                                                                                    <div className="dropdown-content">
                                                                                                        <span onClick={() => handleEdit(thread.id)} >
                                                                                                            <i className="uil uil-clock-three"></i>{text.edit}
                                                                                                        </span>
                                                                                                        <Popconfirm
                                                                                                            title={text.want_to_delete}
                                                                                                            onConfirm={() => handleDeleteThread(thread.id)}
                                                                                                            okText="Yes"
                                                                                                            cancelText="No"
                                                                                                        >
                                                                                                            <span className="remove-item"><i className="uil uil-ban"></i>{text.delete}</span>
                                                                                                        </Popconfirm>
                                                                                                    </div>
                                                                                                </div>
                                                                                            )
                                                                                        }
                                                                                        <Link to={`/forums-react/thread/${thread.id}`}
                                                                                        data-toggle="tooltip"
                                                                                        data-placement="bottom"
                                                                                        title={ thread.title }
                                                                                        className="forum-item-title">
                                                                                            { thread.title }
                                                                                        </Link>
                                                                                        <div className="forum-sub-title"><i>{ thread.created_at2}</i> { thread.profileName }</div>
                                                                                        <div className="forum-sub-title">{ thread.hashtag }</div>
                                                                                        <div className="forum-sub-title">{ thread.content }</div>
                                                                                    </div>
                                                                                    <div className="col-md-1 forum-info">
                                                                                        <span className="views-number">
                                                                                            { thread.views }
                                                                                        </span>
                                                                                        <div>
                                                                                            <small>{text.view}</small>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div className="col-md-1 forum-info">
                                                                                        {
                                                                                            (checkAdmin || thread.checkUpdatedBy == 1) && (
                                                                                                <div className="eps_dots more_dropdown">
                                                                                                    <a href="#"><i className="uil uil-ellipsis-v"></i></a>
                                                                                                    <div className="dropdown-content">
                                                                                                        <span onClick={() => handleEdit(thread.id)} >
                                                                                                            <i className="uil uil-clock-three"></i>{text.edit}
                                                                                                        </span>
                                                                                                        <Popconfirm
                                                                                                            title={text.want_to_delete}
                                                                                                            onConfirm={() => handleDeleteThread(thread.id)}
                                                                                                            okText="Yes"
                                                                                                            cancelText="No"
                                                                                                        >
                                                                                                            <span className="remove-item"><i className="uil uil-ban"></i>{text.delete}</span>
                                                                                                        </Popconfirm>
                                                                                                    </div>
                                                                                                </div>
                                                                                            )
                                                                                        }
                                                                                        <span className="views-number">
                                                                                            { thread.count_comment }
                                                                                        </span>
                                                                                        <div>
                                                                                            <small>{text.comment}</small>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div className="col-md-3 forum-comment-box">
                                                                                    {
                                                                                        thread.lastComment ? (
                                                                                            <div>
                                                                                                <p className="forum-avatar-box">
                                                                                                    <a className="forum-avatar">
                                                                                                        <img className="img-comment" src={ thread.lastCommentAvatar }
                                                                                                            data-toggle="tooltip"
                                                                                                            data-placement="top"/>
                                                                                                    </a>
                                                                                                    <Link to={`/forums-react/thread/${thread.id}`}
                                                                                                    data-toggle="tooltip"
                                                                                                    data-placement="bottom"
                                                                                                    className="forum-permalink">
                                                                                                        { thread.lastCommentCreated }
                                                                                                    </Link>
                                                                                                </p>
                                                                                                <div className="forum-comment">{ thread.lastCommentContent }</div>
                                                                                            </div>
                                                                                        ) : (
                                                                                            <div>
                                                                                                <p className="forum-avatar-box">
                                                                                                    <a className="forum-avatar">
                                                                                                        <img className="img-comment" src={ thread.profileAvatar }
                                                                                                            alt="{{ $user.getFullName() }}"
                                                                                                            data-toggle="tooltip"
                                                                                                            data-placement="bottom" />
                                                                                                    </a>
                                                                                                    <Link to={`/forums-react/thread/${thread.id}`}
                                                                                                    data-toggle="tooltip"
                                                                                                    data-placement="bottom"
                                                                                                    className="forum-permalink">
                                                                                                        { thread.dateCreated }
                                                                                                    </Link>
                                                                                                </p>
                                                                                                <div className="forum-comment">{ thread.content }</div>
                                                                                            </div>
                                                                                        )
                                                                                    }
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        ))
                                                                    }
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                ))
                                            }
                                        </div>
                                    )
                                }
                                </div>
                                ))
                            }
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

export default Forums;
