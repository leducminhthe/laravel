import { Input, Tabs } from 'antd';
import React, { useState, useEffect } from 'react';
import {
    SearchOutlined,
} from '@ant-design/icons';
import { Link } from 'react-router-dom';    

const InfoFriends = ({ auth, listFriend }) => {
    const { TabPane } = Tabs;

    const callback = (key) => {
        console.log(key);
    }

    return (
        <div className="row mx-4 wrraped_content_option">
            <div className="col-12 content_friend bg-white py-3">
                <div className="row">
                    <div className="col-4 d_flex_align">
                        <h3 className='mb-0'><strong>Bạn bè</strong></h3>
                    </div>
                    <div className="col-8">
                        <div className="row d_flex_align">
                            <div className="col-5">
                                <Input placeholder="Tìm kiếm" prefix={<SearchOutlined />} />
                            </div>
                            <div className="col-3">
                                <Link to={`/social-network/info/${auth.user_id}`}>
                                    <p>Lời mời kết bạn</p>
                                </Link>
                            </div>
                            <div className="col-3">
                                <Link to={`/social-network/info/${auth.user_id}`}>
                                    <p>Tìm bạn bè</p>
                                </Link>
                            </div>
                            <div className="col-1">
                                <p><i className="fas fa-ellipsis-h"></i></p>
                            </div>
                        </div>
                    </div>
                    <div className="col-12">
                        <Tabs defaultActiveKey="1" onChange={callback}>
                            <TabPane tab="Tất cả bạn bè" key="1">
                                <div className="row">
                                {
                                    listFriend.map((friend) => (
                                        <div key={friend.id} className="col-6 wrapped_friend">
                                            <div className="row">
                                                <div className="col-4 friend_image">
                                                    <img src={friend.avatar} alt="" width={'100%'} height="120px"/>
                                                </div>
                                                <div className="col-6 friend_name">
                                                    <h4><strong>{ friend.user_name }</strong></h4>
                                                </div>
                                                <div className="col-2 friend_setting">
                                                    <p><i className="fas fa-ellipsis-h"></i></p>
                                                </div>
                                            </div>
                                        </div>
                                    ))
                                }
                                </div>
                            </TabPane>
                            <TabPane tab="Sinh nhật" key="2">
                                Content of Tab Pane 2
                            </TabPane>
                            <TabPane tab="Đang theo dõi" key="3">
                                Content of Tab Pane 3
                            </TabPane>
                        </Tabs>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default InfoFriends;