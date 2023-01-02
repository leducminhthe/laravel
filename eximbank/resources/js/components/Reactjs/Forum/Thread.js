import React, { useState, useEffect } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';
import Axios from 'axios';
import serialize from 'form-serialize';
import { Popconfirm, message } from 'antd';
import {
    DislikeOutlined,
    LikeOutlined
} from '@ant-design/icons';

const Thread = ({text}) => {
    const { id } = useParams();
    let navigate = useNavigate();
    const [dataThread, setDataThread] = useState('');
    const [loading, setLoading] = useState(true);
    const [checkAdmin, setCheckAdmin] = useState('');
    const [comments, setComments] = useState([]);
    const [countComment, setCountComment] = useState('');
    const [countLikeThread, setCountLikeThread] = useState('');
    const [countDislikeThread, setCountDislikeThread] = useState('');

    const handleEdit = (thread_id) => {
        navigate(`/forums-react/edit-thread/${thread_id}`);
    }

    const handleDeleteThread = async (thread_id) => {
        try {
            const items = await Axios.post(`/remove-thread/${thread_id}`)
            .then((response) => {
                if (response.data.status == 'success') {
                    message.success('Xóa thành công');
                    navigate(`/forums-react/topic/${dataThread.forum_id}`);
                }
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const handleDeleteThreadComment = async (comment_id) => {
        try {
            const items = await Axios.post(`/remove-thread-comment/${comment_id}`)
            .then((response) => {
                if (response.data.status == 'success') {
                    message.success('Xóa thành công');
                    fetchDataComment();
                }
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const handleSubmit = async (e) => {
        e.preventDefault();
        var comment = CKEDITOR.instances['content'].getData();
        try {
            const items = await Axios.post(`/send-thread-comment/${id}`, { comment })
            .then((response) => {
                show_message(response.data.message, response.data.status);
                if (response.data.status == 'success') {
                    CKEDITOR.instances.content.setData('');
                    fetchDataComment();
                }
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const fetchDataComment = async () => {
        try {
            const items = await Axios.get(`/data-thread-comment/${id}`)
            .then((response) => {
                setComments(response.data.comments),
                setCountComment(response.data.countComments)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const fetchDataItem = async () => {
        setLoading(true)
        try {
            const items = await Axios.get(`/data-thread/${id}`)
            .then((response) => {
                setDataThread(response.data.thread),
                setCountLikeThread(response.data.thread.count_like_thread),
                setCountDislikeThread(response.data.thread.count_dislike_thread),
                setCheckAdmin(response.data.is_admin),
                setLoading(false),
                runCkeditor();
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const runCkeditor = () => {
        CKEDITOR.replace('content', {
            filebrowserImageBrowseUrl: '/filemanager?type=image',
            filebrowserBrowseUrl: '/filemanager?type=file',
            filebrowserUploadUrl : null, //disable upload tab
            filebrowserImageUploadUrl : null, //disable upload tab
            filebrowserFlashUploadUrl : null, //disable upload tab
        });
    }

    const handleLikeThread = async (thread_id) => {
        try {
            const items = await Axios.post(`/like-dislike-thread/${thread_id}/like`)
                .then((response) => {
                    setCountLikeThread(response.data.count_like_thread),
                    setCountDislikeThread(response.data.count_dislike_thread)
                })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const handleDislikeThread = async (thread_id) => {
        try {
            const items = await Axios.post(`/like-dislike-thread/${thread_id}/dislike`)
                .then((response) => {
                    setCountLikeThread(response.data.count_like_thread),
                    setCountDislikeThread(response.data.count_dislike_thread)

                })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    useEffect(() => {
        fetchDataComment();
        fetchDataItem();
    }, []);

    return (
        <div className="container-fluid" id='forum_thread'>
            <div className="row forum-container">
                <div className="col-xl-12 col-lg-12 col-md-12">
                    <div className="ibox-content forum-container mb-3">
                        <h2 className="st_title">
                            <a href="/">
                                <i className="uil uil-apps"></i>
                                <span>{text.home_page}</span>
                            </a>
                            <i className="uil uil-angle-right"></i>
                            <Link to="/forums-react">{text.forum}</Link>
                            <i className="uil uil-angle-right"></i>
                            {
                                !loading && (
                                <>
                                    <Link to={`/forums-react/topic/${dataThread.forum_id}`}>
                                        { dataThread.forum_category }
                                    </Link>
                                    <i className="uil uil-angle-right"></i>
                                    <span className="font-weight-bold">{ dataThread.title }</span>
                                </>
                                )
                            }
                        </h2>
                    </div>
                </div>
            </div>
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
                    <div className='forum-container bg-white'>
                        <h3 className="f_title">{ dataThread.title }</h3>
                        <div className="topic-item">
                            <div className="row m-0">
                                <div className="col-md-12">
                                {
                                    (checkAdmin.code == 'admin' || dataThread.checkUpdatedBy == 1 ) && (
                                        <div className="eps_dots more_dropdown">
                                            <a href="3"><i className="uil uil-ellipsis-v"></i></a>
                                            <div className="dropdown-content">
                                                <span onClick={() => handleEdit(dataThread.id)} >
                                                    <i className="uil uil-clock-three"></i>{text.edit}
                                                </span>
                                                <Popconfirm
                                                    title={text.want_to_delete}
                                                    onConfirm={() => handleDeleteThread(dataThread.id)}
                                                    okText="Yes"
                                                    cancelText="No"
                                                >
                                                    <span href="#"><i className="uil uil-ban"></i>{text.delete}</span>
                                                </Popconfirm>
                                            </div>
                                        </div>
                                    )
                                }
                                    <div className="forum-avatar">
                                        <img className="img-circle" src={ dataThread.profileAvatar }
                                        data-toggle="tooltip"
                                        data-placement="top" />
                                    </div>
                                    <a className="forum-item-title">{ dataThread.profileName }</a>
                                    <div className="forum-sub-title">{ dataThread.created_at2 }</div>
                                    <div className="forum-sub-title">
                                        <span onClick={() => handleLikeThread(dataThread.id)}>
                                            { countLikeThread }
                                            { <LikeOutlined className='ml-1'/> }
                                        </span>
                                        <span onClick={() => handleDislikeThread(dataThread.id)} className='ml-2'>
                                            { countDislikeThread }
                                            { <DislikeOutlined className='ml-1'/> }
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col-md-12">
                                <div className="forum-content text-justify"
                                dangerouslySetInnerHTML={{ __html: dataThread.content }}
                                >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="row forum-container">
                        <div className="col-xl-12 col-lg-12 col-md-12">
                            <div className="ibox-content forum-container">
                                <h6 className="pl-3"><b>{text.comment}</b> ({ countComment })</h6>
                            </div>
                        </div>
                    </div>
                    {
                        comments.map((item,index) => (
                            <div key={item.id} className="row forum-container" id={index}>
                                <div className="col-xl-12 col-lg-12 col-md-12">
                                    <div className="ibox-content forum-container">
                                        <div className="topic-item">
                                            <div className="row m-0">
                                                <div className="col-md-12">
                                                {
                                                    (checkAdmin.code == 'admin' || item.checkUpdatedBy == 1) && (
                                                        <div className="eps_dots more_dropdown">
                                                            <a href=""><i className="uil uil-ellipsis-v"></i></a>
                                                            <div className="dropdown-content">
                                                                <Popconfirm
                                                                    title={text.want_to_delete}
                                                                    onConfirm={() => handleDeleteThreadComment(item.id)}
                                                                    okText="Yes"
                                                                    cancelText="No"
                                                                >
                                                                    <span className="remove-comment">
                                                                        <i className="uil uil-ban"></i>{text.delete}
                                                                    </span>
                                                                </Popconfirm>

                                                            </div>
                                                        </div>
                                                    )
                                                }
                                                    <div className="forum-avatar">
                                                        <img className="img-circle" src={ item.profileAvatar }
                                                            data-toggle="tooltip"
                                                            data-placement="top" />
                                                    </div>
                                                    <a className="forum-item-title">{ item.profileName }</a>
                                                    <div className="forum-sub-title">{ item.created_at2 }</div>
                                                    <span className="thread_number" >#{ index + 1 }</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="row">
                                            <div className="col-md-12">
                                                <div className="forum-content text-justify ml-4"
                                                dangerouslySetInnerHTML={{ __html: item.comment }}
                                                >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))
                    }
                    <div className="row forum-container">
                        <div className="col-xl-12 col-lg-12 col-md-12">
                            <div className="ibox-content forum-container">
                                <div className="topic-item">
                                    <div className="row m-0">
                                        <div className="col-md-12">
                                            <div className="forum-avatar">
                                                <img className="img-circle" src={ checkAdmin.avatar }
                                                    data-toggle="tooltip"
                                                    data-placement="top"/>
                                            </div>
                                            <a className="forum-item-title">{ checkAdmin.full_name }</a>
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-md-12">
                                        <div className="forum-content">
                                            <form onSubmit={handleSubmit}>
                                                <textarea id="content" name="comment"></textarea>
                                                <br/>
                                                <button type="submit" className="btn btn_adcart">{text.send_post}</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </>
                )
            }
        </div>
    );
};

export default Thread;
