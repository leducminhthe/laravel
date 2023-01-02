import { Button } from 'antd';
import React from 'react';

const Friends = ({ auth }) => {
    return (
        <div className='col-12 wrraped_page_friends'>
            <div className="row">
                <div className="col-3 content_left pl-1">
                    <div className="all_setting pl-2">
                        <h3 className='pl-2'>Bạn bè</h3>
                        <div className='setting cursor_pointer pl-2'>
                            <i className="fas fa-user-friends icon_setting"></i>
                            <span className='ml-2'>Trang chủ</span>
                        </div>
                        <div className='setting cursor_pointer pl-2'>
                            <div className="row">
                                <div className="col-10">
                                    <i className="fas fa-user-friends icon_setting"></i>
                                    <span className='ml-2'>Lời mời kết bạn</span>
                                </div>
                                <div className="col-2 d_flex_align">
                                    <i className="fas fa-angle-right"></i>
                                </div>
                            </div>
                        </div>
                        <div className='setting cursor_pointer pl-2'>
                            <div className="row">
                                <div className="col-10">
                                    <i className="fas fa-user-friends icon_setting"></i>
                                    <span className='ml-2'>Gợi ý</span>
                                </div>
                                <div className="col-2 d_flex_align">
                                    <i className="fas fa-angle-right"></i>
                                </div>
                            </div>
                        </div>
                        <div className='setting cursor_pointer pl-2'>
                            <div className="row">
                                <div className="col-10">
                                    <i className="fas fa-user-friends icon_setting"></i>
                                    <span className='ml-2'>Tất cả bạn bè</span>
                                </div>
                                <div className="col-2 d_flex_align">
                                    <i className="fas fa-angle-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="col-9 content_right pt-4 mb-4">
                    <div className="row m-0">
                        <div className="col-8">
                            <h3><strong>Lời mời kết bạn</strong></h3>
                        </div>
                        <div className="col-4 text-right">
                            Xem tất cả
                        </div>
                    </div>
                    <div className="row mx-0 mt-3">
                        <div className="col-3">
                            <div className="wrapped_friend">
                                <img src={ auth.avatar } alt="" width={'100%'} height={'160px'}/>
                                <div className="info bg-white px-3 pt-2 pb-3">
                                    <div className="name">
                                        <h4><strong>{ auth.firstname }</strong></h4>
                                    </div>
                                    <div className='mb-1'>
                                        <span>1 bạn chung</span>
                                    </div>
                                    <div className="accept mb-2">
                                        <Button className='w-100' type="primary">
                                            Xác nhận
                                        </Button>
                                    </div>
                                    <div className="delete">
                                        <Button className='w-100'>
                                            Xóa
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div className="row m-0">
                        <div className="col-8">
                            <h3><strong>Những người bạn có thể biết</strong></h3>
                        </div>
                        <div className="col-4 text-right">
                            Xem tất cả
                        </div>
                    </div>
                    <div className="row mx-0 mt-3">
                        <div className="col-3">
                            <div className="wrapped_friend_user_know">
                                <img src={ auth.avatar } alt="" width={'100%'} height={'160px'}/>
                                <div className="info bg-white px-3 pt-2 pb-3">
                                    <div className="name">
                                        <h4><strong>{ auth.firstname }</strong></h4>
                                    </div>
                                    <div className='mb-1'>
                                        <span>1 bạn chung</span>
                                    </div>
                                    <div className="accept mb-2">
                                        <Button className='w-100'>
                                            Thêm bạn
                                        </Button>
                                    </div>
                                    <div className="delete">
                                        <Button className='w-100'>
                                            Xóa, gỡ bỏ
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Friends;