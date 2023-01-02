import React, { useState, useEffect } from 'react';
import Axios from 'axios';
import InputEmoji from "react-input-emoji";
import LikeComment from './LikeComment';

const Reply = ({ showReply, comment_id, data_id, auth }) => {
    const [replyComment, setReplyComment] = useState([]);
    const [replyContent, setReplyContent] = useState('');

    const fetchDataReplyComment = async (comment_id) => {
        try {
            const items = await Axios.get(`/data-reply-comment?comment_id=${comment_id}`)
            .then((response) => {
                setReplyComment(response.data.reply)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    useEffect(() => {
        if (showReply.includes(comment_id)) {
            fetchDataReplyComment(comment_id);
        }
    }, [showReply]);

    const handleOnEnterReply = async (data_id, comment_id, reply) => {
        try {
            const items = await Axios.post(`/reply-comment`,{ data_id, comment_id, reply })
            .then((response) => {
                fetchDataReplyComment(comment_id)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    return (
        <div>
        {
            showReply.includes(comment_id) && (
                <>
                    {
                        replyComment.map((reply) => (
                            <div key={reply.id} className='wrapped_reply_comment ml-5'>
                                <div className="reply_comment d-flex mt-2">
                                    <img className='image_profile' src={ reply.avatar } alt="" width={'30px'} height={'30ps'}/>
                                    <div className='ml-2'>
                                        <div className='profile_user_comment'>
                                            <span><strong>{ reply.user_name }</strong></span>
                                            <div><span>{ reply.reply }</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div className='set_reply mt-1 ml-5'>
                                    <LikeComment dataIdLike={reply.id_like_reply} id={reply.id} checkLike={reply.check_like} type={1}/>
                                </div>
                            </div>
                        ))
                    }
                    <div className={`reply d_flex_align reply_comment_${comment_id}`}>
                        <img className='image_profile' src={ auth.avatar } alt="" width={'30px'} height="30px"/>
                        <InputEmoji
                            value={replyContent}
                            onChange={setReplyContent}
                            onEnter={(e) => handleOnEnterReply(data_id, comment_id, e)}
                            cleanOnEnter
                            placeholder="Viết bình luận"
                        />
                    </div>
                </>
            )
        }
        </div>
        
    );
};

export default Reply;