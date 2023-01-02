import React, { useState, useEffect } from 'react';
import { Popover } from 'antd';
import FacebookEmoji from 'react-facebook-emoji';
import Axios from 'axios';

const LikeComment = ({ dataIdLike, id, checkLike, type }) => {
    const [idLikeComment, setIdLikeComment] = useState('');
    const [typeLike, setTypeLike] = useState('');

    const likeHandle = async (id, typeLike) => {
        try {
            if (type == 0 ) {
                const items = await Axios.post(`/like-comment`,{ id, typeLike })
                .then((response) => {
                    setIdLikeComment(id)
                    setTypeLike(typeLike)
                })
            } else {
                const items = await Axios.post(`/like-reply-comment`,{ id, typeLike })
                .then((response) => {
                    setIdLikeComment(id)
                    setTypeLike(typeLike)
                })
            }
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    return (
        <span className={`${typeLike ? `active_like_${typeLike}` : `active_like_${checkLike}`}`}>
            <Popover 
                content={
                    <div className='d_flex_align list_emoji_like'>
                        <span className='m-1 cursor_pointer emoji_like' onClick={(e) => likeHandle(id, 1)}>
                            <FacebookEmoji type="like" size="sm"/>
                        </span>
                        <span className='m-1 cursor_pointer emoji_love' onClick={(e) => likeHandle(id, 2)}>
                            <FacebookEmoji type="love" size="sm"/>
                        </span>
                        <span className='m-1 cursor_pointer emoji_wow' onClick={(e) => likeHandle(id, 3)}>
                            <FacebookEmoji type="wow" size="sm"/>
                        </span>
                        <span className='m-1 cursor_pointer emoji_angry' onClick={(e) => likeHandle(id, 4)}>
                            <FacebookEmoji type="angry" size="sm"/>
                        </span>
                        <span className='m-1 cursor_pointer emoji_haha' onClick={(e) => likeHandle(id, 5)}>
                            <FacebookEmoji type="haha" size="sm"/>
                        </span>
                        <span className='m-1 cursor_pointer emoji_sad' onClick={(e) => likeHandle(id, 6)}>
                            <FacebookEmoji type="sad" size="sm"/>
                        </span>
                    </div>
                }
            >
                {(() => {
                    if ((idLikeComment != dataIdLike && checkLike == 1) || (typeLike == 1 && idLikeComment == id)) {
                        return (
                            <span className='ml-1'>Thích</span>
                        )
                    } else if ((idLikeComment != dataIdLike && checkLike == 2) || (typeLike == 2 && idLikeComment == id)) {
                        return (
                            <span className='ml-1'>Yêu Thích</span>
                        )
                    } else if ((idLikeComment != dataIdLike && checkLike == 3) || (typeLike == 3 && idLikeComment == id)) {
                        return (
                            <span className='ml-1'>Wow</span>
                        )
                    } else if ((idLikeComment != dataIdLike && checkLike == 4) || (typeLike == 4 && idLikeComment == id)) {
                        return (
                            <span className='ml-1'>Phẫn nộ</span>
                        )
                    } else if ((idLikeComment != dataIdLike && checkLike == 5) || (typeLike == 5 && idLikeComment == id)) {
                        return (
                            <span className='ml-1'>Ha ha</span>
                        )
                    } else if ((idLikeComment != dataIdLike && checkLike == 6) || (typeLike == 6 && idLikeComment == id)) {
                        return (
                            <span className='ml-1'>Buồn</span>
                        )
                    } else {
                        return (
                            <span className='cursor_pointer' onClick={(e) => likeHandle(id, 1)}>Thích</span>
                        )
                    }
                })()}
            </Popover>
        </span>
    );
};

export default LikeComment;