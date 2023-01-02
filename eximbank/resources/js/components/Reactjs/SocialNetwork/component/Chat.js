import React, { useState, useEffect } from 'react';
import InputEmoji from "react-input-emoji";
import Axios from 'axios';
import { Spin } from 'antd';

const Chat = ({ listFriend, userChat, parentCallBack, auth, users, hideChat }) => {
    const [chat, setChat] = useState('');
    const [messages, setMessages] = useState([]);
    const [groupChat, setGroupChat] = useState();
    const [loading, setLoading] = useState(true);
    const [send, setSend] = useState(0);

    const handleOnEnter = async (id_chat, chat) =>  {
        var type = 0;
        if (chat) {
            try {
                const items = await Axios.post(`/chat`,{ id_chat, chat, send, type })
                .then((response) => {
                    const message = {
                        group_id: groupChat,
                        post_by_user_id: parseInt(auth.user_id),
                        chat: chat,
                        type: type,
                    };
                    setMessages(messages => [...messages, message])
                    $('.wrapped_none_message').hide()
                    setSend(1);
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }
    }

    const fetchDataChat = async () =>  {
        setLoading(true)
        try {
            const items = await Axios.get(`/data-chat?auth=${auth.user_id}&userChat=${userChat.id_chat}`)
            .then((response) => {
                setLoading(false)
                setMessages(response.data.messages)
                setGroupChat(response.data.group_chat)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }
    
    useEffect(() => {
        if (userChat) {
            fetchDataChat()
        }
    }, [userChat]);

    useEffect(() => {
        if (auth && listFriend.length > 0) {
            window.Echo.join('social')
            .listen('SocialNetworkChat',(event) => {
                if (event.id_chat == auth.user_id) {
                    console.log(event);
                    parentCallBack(event.user_id, 1)
                    setSend(1);
                    if(event.send == 1) {
                        if(event.type == 1) {
                            $('.message_chat').append('<div class="chat_left content_chat"><img src="'+ event.format_image +'" alt="" width="70%"/></div>')
                        } else if (event.type == 2) {
                            $('.message_chat').append('<div class="chat_left content_chat"><img src="'+ event.chat +'" alt="" width="30px"/></div>')
                        } else {
                            $('.message_chat').append('<div class="chat_left content_chat"><span>'+ event.chat +'</span></div>')
                        }
                        updateScroll()
                    } 
                } 
            })
        }
    }, [auth, listFriend]);

    useEffect(() => {
        if (messages.length > 0 ) {
            updateScroll()
        }
    }, [messages]);

    const updateScroll = () => {
        var element = document.getElementById("message_chat");
        element.scrollTop = element.scrollHeight;
    }

    const deleteMessage = async (id) => {
        try {
            const items = await Axios.post(`/delete-chat`,{ id })
            .then((response) => {
                
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const config = {     
        headers: { 'content-type': 'multipart/form-data' }
    }

    const uploadImageChatHandle = async (id_chat, e) => {
        if (!e.target.files || e.target.files.length === 0) {
            return
        } else {
            const data = new FormData() 
            data.append('id_chat', id_chat)
            data.append('chat', e.target.files[0])
            data.append('send', send)
            data.append('type', 1)
            try {
                const items = await Axios.post(`/chat`, data, config)
                .then((response) => {
                    const message = {
                        group_id: groupChat,
                        post_by_user_id: parseInt(auth.user_id),
                        chat: response.data.new_path,
                        type: 1,
                    };
                    setMessages(messages => [...messages, message])
                    $('.wrapped_none_message').hide()
                    setSend(1);
                })
            } catch (error) {
                console.error("Error: " + error.message);
            }
        }
    }

    const upLikeChatHandle = async (id_chat) => {
        const data = new FormData() 
        data.append('id_chat', id_chat)
        data.append('send', send)
        data.append('type', 2)
        try {
            const items = await Axios.post(`/chat`, data, config)
            .then((response) => {
                const message = {
                    group_id: groupChat,
                    post_by_user_id: parseInt(auth.user_id),
                    chat: response.data.like,
                    type: 2,
                };
                setMessages(messages => [...messages, message])
                $('.wrapped_none_message').hide()
                setSend(1);
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    return (
        <>
            {
                userChat && (
                    <div className="wrapped_show_chat bg-white">
                        <div className='header_chat row m-0'>
                            <div className="name_user_chat col-10 pl-1 my-1 d_flex_align">
                                <div className="image_user_chat">
                                    <img className='image_profile' src={ userChat.avatar } alt="" width={'30px'} height="30px"/>
                                    {
                                        users.includes(parseInt(userChat.id_chat)) ? (
                                            <div className='check_online'></div>
                                        ) : (
                                            <div className='offline'></div>
                                        )
                                    }
                                </div>
                                <div className="name_status">
                                    <div className='name'>
                                        <span className='ml-2'><strong>{ userChat.user_name }</strong></span>
                                    </div>
                                    <div className="status ml-2">
                                        {
                                            users.includes(parseInt(userChat.id_chat)) ? (
                                                <span>Đang hoạt động</span>
                                            ) : (
                                                <span>Offline</span>
                                            )
                                        }
                                    </div>
                                </div>
                            </div>
                            <div className='col-2 d_flex_align pl-0 close_hide_chat'>
                                <span className='cursor_pointer' onClick={(e) => hideChat(userChat.id_chat, 1)}>
                                    <i className="fas fa-minus"></i>
                                </span>
                                <span className='cursor_pointer ml-2' onClick={(e) => parentCallBack(userChat.id_chat, 0)}>
                                    <i className="fas fa-times"></i>
                                </span>
                            </div>
                        </div>
                        <div className="message_chat" id='message_chat'>
                        {
                            loading ? (
                                <div className='text-center m-5'>
                                    <Spin />
                                </div>
                            ) : (
                                <>
                                {
                                    messages.length > 0 ? (
                                        <>
                                            {
                                                messages.map((message, key) => (
                                                    <div key={key}>
                                                        {
                                                            message.post_by_user_id == auth.user_id ? (
                                                                <div className="chat_right content_chat">
                                                                    <div className='setting_message' onClick={(e) => deleteMessage(message.id)}><i className="fas fa-trash"></i></div>
                                                                    {
                                                                        message.type == '1' ? (
                                                                            <img className='message_chat_right' src={ message.chat } alt="" width={'70%'}/>
                                                                        ) : message.type == '2' ? (
                                                                            <img className='ml-2' src={ message.chat } alt="" width={'30px'}/>
                                                                        ) : (
                                                                            <span className='message_chat_right'>{ message.chat }</span>
                                                                        )
                                                                    }
                                                                </div>
                                                            ) : (
                                                                <div className="chat_left content_chat">
                                                                    {
                                                                        message.type == '1' ? (
                                                                            <img src={ message.chat } alt="" width={'70%'}/>
                                                                        ) : message.type == '2' ? (
                                                                            <img src={ message.chat } alt="" width={'30px'}/>
                                                                        ) : (
                                                                            <span>{ message.chat }</span>
                                                                        )
                                                                    }
                                                                </div>
                                                            )
                                                        }
                                                    </div>
                                                ))
                                            }
                                        </>
                                    ) : (
                                        <div className="wrapped_none_message">
                                            <img className='image_profile text-center' src={ userChat.avatar } alt="" width={'55px'} height="55px"/>
                                            <div className='user_name'>
                                                <h4 className='my-2'><strong>{ userChat.user_name }</strong></h4>
                                            </div>
                                            <div className="noty">
                                                <span>Các bạn là bạn bè trên mạng xã hội</span>
                                            </div>
                                        </div>
                                    )
                                }
                                </>
                            )
                        }
                        </div>
                        <div className="user_chat">
                            <div className="upload d_flex_align">
                                <div className="upoad_image cursor_pointer">
                                    <label htmlFor="upload-photo" className='cursor_pointer'>
                                        <svg xmlns="http://www.w3.org/2000/svg"  version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 410.309 410.309"><g><g><path style={{fill:"#00ACEA"}} d="M382.955,58.96c16.936,2.176,29.014,17.507,27.167,34.482L386.09,306.079    c-1.848,16.923-17.066,29.144-33.989,27.295c-0.339-0.037-0.677-0.08-1.015-0.128h-1.567V138.372    c0-17.312-14.035-31.347-31.347-31.347H56.947l5.747-52.245c2.179-17.223,17.742-29.535,35.004-27.69L382.955,58.96z"/><path style={{fill:"#00CEB4"}} d="M349.518,333.246v18.808c0,17.312-14.035,31.347-31.347,31.347H31.347    C14.035,383.401,0,369.366,0,352.054v-43.886l86.204-62.694c13.668-10.37,32.794-9.491,45.453,2.09l57.469,50.155    c12.046,10.215,29.238,11.683,42.841,3.657l117.551-68.963V333.246z"/><path style={{fill:"#00EFD1"}} d="M349.518,138.372v94.041l-117.551,68.963c-13.603,8.026-30.795,6.558-42.841-3.657l-57.469-50.155    c-12.659-11.58-31.785-12.46-45.453-2.09L0,308.168V138.372c0-17.312,14.035-31.347,31.347-31.347h286.824    C335.484,107.026,349.518,121.06,349.518,138.372z"/></g><circle style={{fill:"#D4E1F4"}} cx="208.98" cy="192.707" r="33.437"/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
                                    </label>
                                    <input type="file" onChange={(e) => uploadImageChatHandle(userChat.id_chat, e)} className="upload_image_cover" id="upload-photo"/>
                                </div>
                                <div className="upload_like ml-2 cursor_pointer" onClick={(e) => upLikeChatHandle(userChat.id_chat)}>
                                    <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" enableBackground="new 0 0 100 100" height="512" viewBox="0 0 100 100" width="512"><g id="_x30_6.Like"><path d="m50 10c-22.09 0-40 17.91-40 40s17.91 40 40 40 40-17.91 40-40-17.91-40-40-40zm-10.07 56.64c0 1.53-1.26 2.79-2.8 2.79h-7.68c-1.53 0-2.79-1.26-2.79-2.79v-21.9c0-1.53 1.26-2.79 2.79-2.79h7.68c1.54 0 2.8 1.26 2.8 2.79zm32.69-17.16c.87.7 1.43 1.77 1.43 2.98 0 1.81-1.26 3.32-2.95 3.72.87.7 1.43 1.77 1.43 2.97 0 1.82-1.26 3.33-2.96 3.72.88.7 1.44 1.77 1.44 2.98 0 2.11-1.71 3.82-3.82 3.82l-21.64-.24c-1.54 0-2.8-1.26-2.8-2.79v-21.9c0-3.48 11.05-9.83 11.64-13.27.38-2.2-.12-7.85 1.4-7.85 2.58 0 5.89.99 5.89 6.72 0 5.05-3.31 11.61-3.31 11.61h13.39c2.11 0 3.82 1.71 3.82 3.81 0 1.82-1.27 3.33-2.96 3.72z" fill="#008ae8"/></g></svg>
                                </div>
                            </div>
                            <InputEmoji
                                value={chat}
                                onChange={setChat}
                                onEnter={(e) => handleOnEnter(userChat.id_chat, e)}
                                cleanOnEnter
                                placeholder="Aa"
                            />
                        </div>
                    </div>
                )
            }
        </>
        
    );
};

export default Chat;