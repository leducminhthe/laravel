import React, { useState, useEffect } from 'react';
import Axios from 'axios';
import { Button, Input } from 'antd';
import {
    SearchOutlined,
    ReloadOutlined,
    UserAddOutlined,
    LoadingOutlined,
    CloseOutlined,
} from '@ant-design/icons';
import 'video-react/dist/video-react.css';
import Chat from './component/Chat';
import { Link } from 'react-router-dom';    
import Post from './component/Post';

const SocialNetwork = ({ auth, listFriend }) => {
    const [dataNews, setDataNews] = useState([]);
    const [users, setUsers] = useState([]);
    const [listFriendUserKnow, setListFriendUserKnow] = useState([]);
    const [loading, setLoading] = useState(true);
    const [page, setPage] = useState(2);
    const [hasMore, sethasMore] = useState(true);
    const [addFriend, setAddFriend] = useState('');
    const [reloadList, setReloadList] = useState(false);
    const [userChat, setUserChat] = useState('');
    const [hideChat, setHideChat] = useState([]);
    const [showInputSearch, setShowInputSearch] = useState(false);
    
    const fetchDataListFriendUserKnow = async () => {
        try {
            const items = await Axios.get(`/data-list-friend-user-know`)
            .then((response) => {
                setListFriendUserKnow(response.data.list_friends_user_know)
                setReloadList(false)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const fetchDataSocialNetworkNews = async (reload) => {
        if(reload == 0) {
            setLoading(true)
        }
        try {
            const items = await Axios.get(`/data-news-network?page=1&type=1`)
            .then((response) => {
                setDataNews(response.data.news.data)
                if(reload == 0) {
                    setLoading(false)
                }
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    const fetchDataScroll = async () => {
        const res = await Axios.get(`/data-news-network?page=${page}&type=1`)
        return res
    };

    const fetchData = async () => {
        if (dataNews.length > 0) {
            const dataFormServer = await fetchDataScroll()
            setDataNews([...dataNews, ...dataFormServer.data.news.data])
            if (dataFormServer.data.news.data.length === 0 || dataFormServer.data.news.data.length.length < 6) {
                sethasMore(false)
            }
            setPage(page + 1)
        }
    };

    useEffect(() => {
        if (auth) {
            window.Echo.join('social')
            .here(user => {
                user.forEach(element => {
                    setUsers(users => [...users, element.id])
                });
            })
            .joining(user => {
                setUsers(users => [...users, user.id])
            })
            .leaving(user => {
                setUsers((users) => users.filter(u => u != user.id))
            })
            .listen('SocialNetWork',(event) => {
                console.log(event);
                fetchDataSocialNetworkNews(1)
            })

            fetchDataSocialNetworkNews(0)
            fetchDataListFriendUserKnow()
        }
    }, [auth]);

    
    const addFriendHandle = async (user_id) => {
        try {
            const items = await Axios.post(`/user-add-friend`,{ user_id })
            .then((response) => {
                setAddFriend(addFriend => [...addFriend, user_id])
            })
        } catch (error) {
            console.error("Error: " + error.message)
        }
    }

    const reloadListHandle = () => {
        setReloadList(true)
        fetchDataListFriendUserKnow()
    }

    const showChatHandle = (id_chat, type) => {
        if (type == 1) {
            setUserChat(listFriend.find(id => id.id_chat == id_chat))
            if(hideChat.length > 0) {
                setHideChat((hideChat) => hideChat.filter(id => id.id_chat != id_chat))
            }
        } else {
            setUserChat('')
        }
    }

    const hideChatHandle = (id_chat, type) => {
        if (type == 1) {
            var friend = listFriend.find(id => id.id_chat == id_chat)
            setHideChat(hideChat => [...hideChat, friend])
            setUserChat('');
        } else {
            setHideChat((hideChat) => hideChat.filter(id => id.id_chat != id_chat))
        }
    }

    return (
        <div className='col-12'>
            <div className="row">
                <div className="col-12 wrapped_content pb-4">
                    <div className="row">
                        <div className="col-3 wrapped_content_left">
                            <div className="content_left">
                                <Link to={`/social-network/info/${auth.user_id}`}>
                                    <div className="icon_name mb-3 p-2">
                                        <img className='image_profile' src={auth.avatar} alt="" width="35px" height="35px"/>
                                        <span className='name ml-2'>{ auth.firstname }</span>
                                    </div>
                                </Link>
                                <div className='friend mb-3 p-2'>
                                    <Link to={`/social-network/friends`}>
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512 512" ><g><path style={{fill:"#006EFF"}} d="M450.017,151.317c0.37,45.655-34.426,83.08-77.571,83.44h-0.69   c-20.848-0.02-40.425-8.589-55.214-24.167c-14.668-15.468-22.857-36.066-23.037-57.983c-0.38-45.655,34.426-83.08,77.571-83.44   h0.68C414.571,69.217,449.637,105.903,450.017,151.317z"/><path style={{fill:"#006EFF"}} d="M512,389.829v25.657H231.523v-25.657c0-77.331,62.913-140.244,140.234-140.244   C449.087,249.586,512,312.498,512,389.829z"/></g><g><path style={{fill:"#005FE4"}} d="M371.756,234.757V69.167c42.815,0.05,77.881,36.736,78.261,82.15   c0.37,45.655-34.426,83.08-77.571,83.44H371.756z"/><path style={{fill:"#005FE4"}} d="M512,389.829v25.657H371.756V249.586C449.087,249.586,512,312.498,512,389.829z"/></g><g><path style={{fill:"#9CFDFF"}} d="M256.72,151.957c0,53.244-40.855,96.549-91.079,96.549s-91.089-43.305-91.089-96.549   c0-53.234,40.865-96.539,91.089-96.539S256.72,98.723,256.72,151.957z"/><path style={{fill:"#9CFDFF"}} d="M331.271,428.765v27.817H0v-27.817c0-91.329,74.301-165.641,165.641-165.641   C256.97,263.124,331.271,337.435,331.271,428.765z"/></g><g><path style={{fill:"#00F8FE"}} d="M165.641,248.506V55.418c50.224,0,91.079,43.305,91.079,96.539   C256.72,205.201,215.865,248.506,165.641,248.506z"/><path style={{fill:"#00F8FE"}} d="M331.271,428.765v27.817H165.641V263.124C256.97,263.124,331.271,337.435,331.271,428.765z"/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
                                        <span className='title_friend ml-2'>Bạn bè</span>
                                    </Link>
                                </div>
                                <div className="group_friend mb-3 p-2">
                                    <Link to={`/social-network/groups`}>
                                        <svg xmlns="http://www.w3.org/2000/svg" id="Layer_3" viewBox="0 0 512 512" data-name="Layer 1"><circle cx="256" cy="256" fill="#2196f3" r="256"/><path d="m366.624 128.724a41.887 41.887 0 1 1 -27.3 73.667l-.073.09c.091-1.595.139-3.207.139-4.819a82.832 82.832 0 0 0 -11.7-42.541 41.865 41.865 0 0 1 38.931-26.4zm73.6 154.43v30.2h-63.542a123.136 123.136 0 0 0 -13.6-34.482 124.545 124.545 0 0 0 -35.362-38.7 82.968 82.968 0 0 0 5.2-10.279 68.1 68.1 0 0 0 76.8-6.456 73.6 73.6 0 0 1 30.51 59.531v.188zm-86.709 58c0-.05 0-.1 0-.14a98 98 0 0 0 -42.715-80.564 83.179 83.179 0 0 1 -109.5 0 98.086 98.086 0 0 0 -42.722 80.574v.132 42.121h194.939v-42.121zm-218.1-27.8h-63.639v-30.2c0-.049 0-.114 0-.172a73.6 73.6 0 0 1 30.51-59.547 68.1 68.1 0 0 0 76.891 6.406 82.747 82.747 0 0 0 5.213 10.329 123.456 123.456 0 0 0 -48.971 73.182zm9.96-184.628a41.864 41.864 0 0 1 38.971 26.505 82.773 82.773 0 0 0 -11.646 42.431c0 1.661.058 3.314.157 4.951l-.2-.222a41.888 41.888 0 1 1 -27.285-73.667zm110.675 11.906a57.031 57.031 0 1 1 -57.032 57.03 57.092 57.092 0 0 1 57.032-57.03z" fill="#fff" fillRule="evenodd"/></svg>   
                                        <span className='title_group_friend ml-2'>Nhóm</span>
                                    </Link>
                                </div>
                                <div className="watch_video mb-3 p-2">
                                    <Link to={`/social-network/videos`}>
                                        <svg xmlns="http://www.w3.org/2000/svg" id="Capa_1" viewBox="0 0 512 512"><g><path d="m462.452 61.936h-412.904c-12.69 0-24.266 4.769-33.032 12.615l-8.362 36.934v240.088c0 27.365 22.183 49.549 49.548 49.549h404.749l36.934-8.863c7.845-8.766 12.614-20.343 12.614-33.032v-247.743c.001-27.365-22.182-49.548-49.547-49.548z" fill="#4bc3ef"/><path d="m412.903 450.064h-313.806c-4.565 0-8.258-3.698-8.258-8.258s3.694-8.258 8.258-8.258h313.806c4.565 0 8.258 3.698 8.258 8.258s-3.693 8.258-8.258 8.258z" fill="#4bc3ef"/><path d="m342.068 220.582-128.23-64.115c-1.147-.574-2.314-1.008-3.487-1.298l-13.238 16.07v128.23l16.725 6.78 128.23-64.115 5.317-17.388c-1.391-1.657-3.164-3.087-5.317-4.164z" fill="#fff"/><path d="m49.548 392.258c-18.243 0-33.032-14.789-33.032-33.032v-284.676c-10.115 9.072-16.516 22.278-16.516 36.934v247.742c0 27.365 22.183 49.548 49.548 49.548h412.903c14.656 0 27.864-6.401 36.934-16.516z" fill="#1badde"/><path d="m246.204 275.369c-16.472 8.236-35.854-3.742-35.854-22.159v-98.042c-10.053-2.49-20.415 5.073-20.415 16.071v128.231c0 12.278 12.921 20.263 23.902 14.772l128.23-64.115c10.014-5.007 11.778-17.664 5.317-25.38z" fill="#e3f5ff"/></g></svg>
                                        <span className="title_watch_video ml-2">Video</span>
                                    </Link>
                                </div>
                                <div className="history mb-3 p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><g id="reload"><circle cx="16" cy="16" fill="#e6ecff" r="9"/><path d="m25 16a8.999 8.999 0 0 1 -17.59 2.68 47.1657 47.1657 0 0 0 16.5-6.97 8.9046 8.9046 0 0 1 1.09 4.29z" fill="#b7c6e2"/><path d="m18.8281 17.4141-1.8281-1.8282v-2.5859a1 1 0 0 0 -2 0v3a1.0133 1.0133 0 0 0 .2938.7079l2.12 2.12a1 1 0 0 0 1.414-1.414z" fill="#4294ff"/><path d="m16 2a1 1 0 0 0 0 2 12.05 12.05 0 1 1 -8.3131 3.3744l-.0678.5172a1 1 0 0 0 1.9834.26l.3892-2.9746a1.0249 1.0249 0 0 0 -.8623-1.1211l-2.9746-.3889a1 1 0 1 0 -.2588 1.9824l.6227.0816a13.9753 13.9753 0 1 0 9.4813-3.731z" fill="#4294ff"/><path d="m18.83 18.83a1.0143 1.0143 0 0 1 -1.42 0l-2.12-2.12a1.0323 1.0323 0 0 1 -.21-.33c0-.01-.01-.01-.01-.02.63-.24 1.27-.51 1.93-.8v.03l1.83 1.82a1.008 1.008 0 0 1 0 1.42z" fill="#2965ed"/></g></svg>
                                    <span className="title_history">Kỹ niệm</span>
                                </div>
                            </div>
                        </div>
                        <div className="col-6 content content_news">
                            <Post auth={auth} dataNews={dataNews} fetchData={fetchData} loading={loading} hasMore={hasMore} users={users} listFriend={listFriend}/>
                        </div>
                        <div className="col-3 wrapped_content_right">
                            <div className="content_right row">
                                <div className="col-12 p-0">
                                    <div className='row m-0'>
                                        <div className="col-10 pl-0">
                                            <h4>Những người bạn có thể biết</h4>
                                        </div>
                                        <div className="col-2 text-right">
                                            <div className='reload_list cursor_pointer'>
                                                {
                                                    reloadList ? (
                                                        <span><LoadingOutlined /></span>
                                                    ) : (
                                                        <span onClick={reloadListHandle}><ReloadOutlined /></span>
                                                    )
                                                }
                                            </div>
                                        </div>
                                        <div className="list_friend_user_know col-12 p-0 my-2">
                                        {
                                            listFriendUserKnow.map((friend) => (
                                                <div key={friend.id} className="wrapped_list_friend row mx-0 mb-1">
                                                    <div className="d_flex_align col-8 pl-0">
                                                        <div className="avatar_friend">
                                                            <img className='image_profile' src={ friend.avatar } alt="" width={'35px'} height="35px"/>
                                                        </div>
                                                        <span className='ml-2'>{friend.firstname}</span>
                                                    </div>
                                                    <div className="col-4 px-1 d_flex_align">
                                                        {
                                                            addFriend.includes(friend.id) ? (
                                                                <Button disabled className='btn_add_friend w-100 d_flex_align' type="primary" shape="round" icon={<UserAddOutlined />}>
                                                                    Yêu cầu
                                                                </Button>
                                                            ) : (
                                                                <Button className='btn_add_friend w-100 d_flex_align' type="primary" shape="round" icon={<UserAddOutlined />} onClick={(e) => addFriendHandle(friend.id)}>
                                                                    Thêm bạn
                                                                </Button>
                                                            )
                                                        }
                                                    </div>
                                                </div>
                                            ))
                                        }
                                        </div>
                                    </div>
                                </div>
                                <div className="list_friend mt-1 col-12">
                                    <div className='row mb-2 wrraped_search_friend'>
                                        <div className="col-4 px-0 pt-2">
                                            <h4>Người liên hệ</h4>
                                        </div>
                                        <div className="col-8 text-right pl-1 pr-2 search_friend">
                                            {
                                                showInputSearch ? (
                                                    <Input addonAfter={<CloseOutlined onClick={() => setShowInputSearch(false)}/>}  defaultValue="mysite" />
                                                ) : (
                                                    <SearchOutlined onClick={() => setShowInputSearch(true)}/>
                                                )
                                            }
                                        </div>
                                    </div>
                                    <div className="row">
                                    {
                                        listFriend.map((friend) => (
                                            <div key={friend.id_chat} className="col-12 p-1 cursor_pointer">
                                                <div className="friend d_flex_align mb-1" onClick={(e) => showChatHandle(friend.id_chat, 1)}>
                                                    <div className="avatar_friend">
                                                        <img className='image_profile' src={ friend.avatar } alt="" width={'35px'} height="35px"/>
                                                        {
                                                            users.includes(parseInt(friend.id_chat)) ? (
                                                                <div className='check_online'></div>
                                                            ) : (
                                                                <div className='offline'></div>
                                                            )
                                                        }
                                                    </div>
                                                    <span className='ml-2'>{friend.user_name}</span>
                                                </div>
                                            </div>
                                        ))
                                    }
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <Chat listFriend={listFriend} userChat={userChat} parentCallBack={showChatHandle} auth={auth} users={users} hideChat={hideChatHandle}/>
            <div className="wrapped_hide_chat">
                {
                    hideChat.map((hide, key) => (
                        <div key={key} style={{ bottom: key * 50 }} className="image_user_hide cursor_pointer">
                            <span className='close_hide' onClick={(e) => hideChatHandle(hide.id_chat, 0)}><i className="fas fa-times"></i></span>
                            <img className='image_profile' src={hide.avatar} alt="" width="45px" height="45px" onClick={(e) => showChatHandle(hide.id_chat, 1)}/>
                            {
                                users.includes(parseInt(hide.id_chat)) ? (
                                    <div className='check_online'></div>
                                ) : (
                                    <div className='offline'></div>
                                )
                            }
                        </div>
                    ))
                }
            </div>
        </div>
    );
};

export default SocialNetwork;