import React, { useState, useEffect } from 'react';
import Axios from 'axios';
import InputEmoji from "react-input-emoji";
import { Spin } from 'antd';
import Reply from './Reply';
import LikeComment from './LikeComment';

const Comment = ({ showCommentNewId, showCommentNew, data_id, auth }) => {
    const [dataNewComments, setDataNewComments] = useState([]);
    const [loading, setLoading] = useState(true);
    const [lastPage, setLastPage] = useState('');
    const [perPage, setPerPage] = useState('');
    const [page, setPage] = useState(2);
    const [comment, setComment] = useState('');
    const [showReply, setShowReply] = useState([]);

    const fetchDataListComment = async (type) => {
        if (type == 0) {
            setLoading(true)
        }
        try {
            const items = await Axios.get(`/show-comment/${data_id}?page=1`)
            .then((response) => {
                setDataNewComments(response.data.comments.data)
                setLastPage(response.data.comments.last_page)
                setPerPage(response.data.comments.per_page)
                setLoading(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    useEffect(() => {
        if (showCommentNewId == data_id) {
            fetchDataListComment(0);
        }
    }, [showCommentNewId]);

    const handleOnEnter = async (id, comment) =>  {
        if (comment) {
            try {
                const items = await Axios.post(`/user-comment-network`,{ id, comment })
                .then((response) => {
                    fetchDataListComment(1)
                    $('.total_comment').html(response.data.total_comment + ' bình luận');
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }
    }

    const fetchComment = async () => {
        const res = await Axios.get(`/show-comment/${data_id}?page=${page}`)
        const dataFormServer = res;
        setDataNewComments([...dataNewComments, ...dataFormServer.data.comments.data]);
        setPage(page + 1)
        setPerPage(perPage + 1)
    };

    const showReplyHandle = (comment_id) => {
        setShowReply(showReply => [...showReply, comment_id]);
    }

    return (
        <div className='wrapped_comment'>
        {
            showCommentNew.includes(data_id) && (
                <>
                {
                    loading ? (
                        <div className='text-center m-2'>
                            <Spin />
                        </div>
                    ) : (
                    <>
                        <hr />
                        <div className='auth_comment d_flex_align'>
                            <img className='image_profile' src={ auth.avatar } alt="" width={'35px'} height="35px"/>
                            <InputEmoji
                                value={comment}
                                onChange={setComment}
                                cleanOnEnter
                                onEnter={(e) => handleOnEnter(data_id, e)}
                                placeholder="Viết bình luận"
                            />
                        </div>
                        <div className={`mt-2 list_comment_${data_id}`}>
                            {
                                dataNewComments.length > 0 && (
                                    <>
                                        {
                                            dataNewComments.map((comment) => (
                                                <div key={comment.id} className="mb-3">
                                                    <div  className="d-flex">
                                                        <img className='image_profile' src={ comment.avatar } alt="" width={'35px'} height={'35ps'}/>
                                                        <div className='ml-2'>
                                                            <div className='profile_user_comment'>
                                                                <span><strong>{ comment.user_name }</strong></span>
                                                                <div><span>{ comment.comment }</span></div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div className='set_comment ml-5 mt-2'>
                                                        <LikeComment dataIdLike={comment.id_like_comment} id={comment.id} checkLike={comment.check_like} type={0}/>
                                                        <span className='ml-2 cursor_pointer' onClick={(e) => showReplyHandle(comment.id)}>
                                                            { comment.total_reply }
                                                            <span className='ml-1'>Phản hồi</span>
                                                        </span>
                                                    </div>
                                                    <Reply showReply={showReply} comment_id={comment.id} data_id={data_id} auth={auth}/>
                                                </div>
                                            ))
                                        }
                                        {
                                            perPage < lastPage && (
                                                <span className='ml-1 cursor_pointer see_more_comment' onClick={fetchComment}>Xem thêm bình luận</span>
                                            )
                                        }
                                    </>
                                ) 
                            }
                        </div>
                    </>
                    )
                }
                </>
            ) 
        }
        </div>
    );
};

export default Comment;