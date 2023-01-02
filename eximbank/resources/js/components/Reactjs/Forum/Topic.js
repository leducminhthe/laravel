import React, { useState, useEffect } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';    
import Axios from 'axios';
import { Popconfirm, message } from 'antd';

const Topic = ({text}) => {
    let navigate = useNavigate();
    const { id } = useParams();
    const [dataForumThread, setDataForumThread] = useState([]);
    const [dataTopic, setDataTopic] = useState('');
    const [loading, setLoading] = useState(true);
    const [checkAdmin, setCheckAdmin] = useState('');

    const handleEdit = (thread_id) => {
        console.log(thread_id);
        navigate(`/forums-react/edit-thread/${thread_id}`);
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

    const fetchDataItem = async () => {
        setLoading(true)
        try {
            const items = await Axios.get(`/data-topic/${id}`)
            .then((response) => {
                setDataTopic(response.data.topic),
                setDataForumThread(response.data.forum_thread),
                setCheckAdmin(response.data.is_admin),
                setLoading(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    useEffect(() => {
        fetchDataItem();
    }, []);

    return (
        <div className="container-fluid forum-container">
            <div className="row m-0">
                <div className="col-xl-12 col-lg-12 col-md-12">
                    <div className="ibox-content forum-container">
                        <h2 className="st_title mb-4">
                            <a href="/">
                                <i className="uil uil-apps"></i>
                                <span>{text.home_page}</span>
                            </a>
                            <i className="uil uil-angle-right"></i>
                            <Link to="/forums-react">{text.forum}</Link>
                            <i className="uil uil-angle-right"></i>
                            <span className="font-weight-bold">{ dataTopic.name }</span>
                        </h2>
                        <Link className="btn subscribe-btn ml-3" to={`/forums-react/create-thread/${dataTopic.id}`}>
                            {text.send_new_posts}
                        </Link>
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
                                dataForumThread.map((item) => (
                                    <div key={item.id} className="forum-item">
                                        <div className="row m-0">
                                            <div className="col-md-7">
                                                <div className="forum-avatar">
                                                    <img className="img-circle" src={ item.profileAvatar }
                                                        data-toggle="tooltip"
                                                        data-placement="top" />
                                                </div>
                                                {
                                                    (checkAdmin || item.checkUpdatedBy == 1 ) && (
                                                        <div className="eps_dots more_dropdown tool">
                                                            <a href="3"><i className="uil uil-ellipsis-v"></i></a>
                                                            <div className="dropdown-content">
                                                                <span onClick={() => handleEdit(item.id)} >
                                                                    <i className="uil uil-clock-three"></i>{text.edit}
                                                                </span>
                                                                <Popconfirm
                                                                    title={text.want_to_delete}
                                                                    onConfirm={() => handleDeleteThread(item.id)}
                                                                    okText="Yes"
                                                                    cancelText="No"
                                                                >
                                                                    <span className="remove-item"><i className="uil uil-ban"></i>{text.delete}</span>
                                                                </Popconfirm>
                                                            </div>
                                                        </div>
                                                    )
                                                }
                                                <Link to={`/forums-react/thread/${item.id}`} 
                                                className="forum-item-title"
                                                >
                                                    { item.title }
                                                </Link>
                                                <div className="forum-sub-title">{ item.created_at2 + ' ' + item.profileName }</div>
                                                <div className="forum-sub-title">{ item.hashtag }</div>
                                                <div className="forum-sub-title">{ item.content }</div>
                                            </div>
                                            <div className="col-md-1 forum-info">
                                                <span className="views-number">
                                                    { item.views }
                                                </span>
                                                <div>
                                                    <small>{text.view}</small>
                                                </div>
                                            </div>
                                            <div className="col-md-1 forum-info">
                                            {
                                                (checkAdmin || item.checkUpdatedBy == 1 ) && (
                                                    <div className="eps_dots more_dropdown">
                                                        <a href="#"><i className="uil uil-ellipsis-v"></i></a>
                                                        <div className="dropdown-content">
                                                            <span onClick={() => handleEdit(item.id)} >
                                                                <i className="uil uil-clock-three"></i>{text.edit}
                                                            </span>
                                                            <Popconfirm
                                                                title={text.want_to_delete}
                                                                onConfirm={() => handleDeleteThread(item.id)}
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
                                                    { item.countThreadComment }
                                                </span>
                                                <div>
                                                    <small>{text.comment}</small>
                                                </div>
                                            </div>
                                            <div className="col-md-3 forum-comment-box">
                                            {
                                                item.lastComment ? (
                                                    <div>
                                                        <p className="forum-avatar-box">
                                                            <a className="forum-avatar">
                                                                <img className="img-comment" src={ item.lastCommentAvatar }
                                                                    data-toggle="tooltip"
                                                                    data-placement="top"/>
                                                            </a>
                                                            <Link to={`/forums-react/thread/${item.id}`}
                                                            data-toggle="tooltip"
                                                            data-placement="bottom"
                                                            className="forum-permalink">
                                                                { item.lastCommentCreated }
                                                            </Link>
                                                        </p>
                                                        <div className="forum-comment">{ item.lastCommentContent }</div>
                                                    </div>
                                                ) : (
                                                    <div>
                                                        <p className="forum-avatar-box">
                                                            <a className="forum-avatar">
                                                                <img className="img-comment" src={ item.profileAvatar }
                                                                    data-toggle="tooltip"
                                                                    data-placement="bottom" />
                                                            </a>
                                                            <Link to={`/forums-react/thread/${item.id}`}
                                                            data-toggle="tooltip"
                                                            data-placement="bottom"
                                                            className="forum-permalink">
                                                                { item.dateCreated }
                                                            </Link>
                                                        </p>
                                                        <div className="forum-comment">{ item.content }</div>
                                                    </div>
                                                )
                                            }
                                            </div>
                                        </div>
                                    </div>
                                ))
                            }
                            </>
                            )
                        }
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Topic;