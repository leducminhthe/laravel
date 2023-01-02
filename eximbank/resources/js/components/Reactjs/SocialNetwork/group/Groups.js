import React from 'react';
import { Input, Tooltip, Dropdown, Menu } from 'antd';
import { SearchOutlined, EllipsisOutlined } from '@ant-design/icons';
import { Link } from 'react-router-dom';    
import { Player } from 'video-react';
import LikePost from '../component/LikePost';
import LazyLoad from 'react-lazyload';

const Groups = ({ auth }) => {
    return (
        <div className='col-12 wrraped_page_groups'>
            <div className="row h_100">
                <div className="col-3 content_left pl-1">
                    <div className="all_setting pl-2">
                        <h3 className='pl-2'>Nhóm</h3>
                        <div className='setting cursor_pointer pl-2 mb-2'>
                            <Input placeholder="Tìm kiếm video" prefix={<SearchOutlined />} allowClear/>
                        </div>
                        <div className='row setting cursor_pointer pl-2'>
                            <div className="col-12">
                                <div className='my-2'>
                                    <h4>Tạo nhóm mới</h4>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div className='row setting cursor_pointer pl-2'>
                            <div className="col-12">
                                <div className='my-2'>
                                    <h4>Nhóm bạn đã tham gia</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="col-9 content_right pt-4 mb-4">
                    <div className="row m-0">
                        <div className="col-12">
                            <h3><strong>Hoạt động gần đây</strong></h3>
                        </div>
                    </div>
                    <div className="wrraped_post row">
                        <div className='col-12 mb-2 p-2 bg-white'>
                            <div className="top_new row">
                                <div className="wrapped_user col-10">
                                    <div className="icon_user mr-2">
                                        <Link to={``}>
                                            <img className='image_profile' src={ auth.avatar } alt="" width={'40px'} height="40px"/>
                                        </Link>
                                    </div>
                                    <div className='name_user'>
                                        <Link to={``}>
                                            <div>
                                                <span><strong>{ auth.firstname }</strong></span>
                                            </div>
                                        </Link>
                                        <div className="time_add_new">
                                            {/* <span>{ data.created_at2 }</span> */}
                                            <span className='ml-2'>
                                                {/* {
                                                    data.status == 1 ? (
                                                        <Tooltip title="Công khai">
                                                            <i className="fas fa-globe-americas"></i>
                                                        </Tooltip>
                                                    ) : data.status == 2 ? (
                                                        <Tooltip title="Bạn bè">
                                                            <i className="fas fa-user-friends mr-1"></i>
                                                        </Tooltip>
                                                    ) : (
                                                        <Tooltip title="Cá nhân">
                                                            <i className="fas fa-lock mr-1"></i>
                                                        </Tooltip>
                                                    )
                                                } */}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div className="setting col-2 text-right cursor_pointer">
                                    <Dropdown trigger={['click']}
                                        placement="bottomRight"
                                        overlay={
                                            <Menu>
                                                <Menu.Item key="0">
                                                    <span>Lưu bài viết</span>
                                                </Menu.Item>
                                                <Menu.Item key="1">
                                                    <span>Ẩn bài viết</span>
                                                </Menu.Item>
                                            </Menu>
                                        }
                                    >
                                        <EllipsisOutlined className='mr-3'/>
                                    </Dropdown>
                                </div>
                            </div>
                            <div className="text_new pl-2 my-2">
                                <span></span>
                            </div>
                            <hr />
                            <div className="video_new">
                                <LazyLoad once={true}>
                                    <Player>
                                        <source src={''} />
                                    </Player>
                                </LazyLoad>
                            </div>
                            
                            <div className="wrapped_view_like_comment_share row mt-2 mx-0">
                                <div className="col-6 total_like pr-0 pl-1">
                                    <i className="fas fa-thumbs-up"></i>
                                    <span className={`ml-1`}>
                                        
                                    </span>
                                </div>
                                <div className="col-6 wrapped_comment_share pr-1 pl-0 d_flex_align">
                                    <div className='total_comment'>bình luận</div>
                                    <div className='ml-2 total_share'>lượt chia sẽ</div>
                                    <div className='ml-2 total_share'>lượt xem</div>
                                </div>
                            </div>
                            <hr />
                            <div className='row m-0'>
                                <LikePost data_id={1} data_check_like={0} data_id_like_new={1}/>
                                <div className={`col-4 comment_new text-center cursor_pointer`} onClick={(e) => showListComment()}>
                                    <i className="far fa-comment-alt"></i>
                                    <span className='ml-1'>Bình luận</span>
                                </div>
                                <div className={`col-4 share_new text-center cursor_pointer`} onClick={(e) => shareNew()}>
                                    <i className="far fa-share-square"></i>
                                    <span className='ml-1'>Chia sẽ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Groups;