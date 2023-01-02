import { CheckCircleFilled, CheckCircleOutlined, SearchOutlined } from '@ant-design/icons';
import { Button, Input, Modal, Radio, Space } from 'antd';
import React, { useState } from 'react';

const ModalType = ({ isModalVisible, handleOk, handleCancel, setType, type, onClickShowModal, listFriend }) => {
    const [valueType, setValueType] = useState(type);
    const [isModalChooseFriendVisible, setIsModalChooseFriendVisible] = useState(false);
    const [searchFriend, setSearchFriend] = useState('');
    const [chooseFriend, setChooseFriend] = useState([]);
    
    const onChange = e => {
        setType(e.target.value);
        setValueType(e.target.value)
        if(e.target.value == 2) {
            setIsModalChooseFriendVisible(true)
        }
    };

    const icon = (
        valueType == 1 ? (
            <i className="fas fa-globe-asia mr-2"></i>
        ) : valueType == 2 ? (
            <i className="fas fa-user-friends mr-2"></i>
        ) : (
            <i className="fas fa-lock mr-2"></i>
        )
    )

    const handleChooseFriendCancel = () => {
        setIsModalChooseFriendVisible(false);
    };

    const handleChooseFriend = (friendId) => {
        if (chooseFriend.includes(friendId)) {
            setChooseFriend((chooseFriend) => chooseFriend.filter(id => id != friendId))
        } else {
            setChooseFriend(chooseFriend => [...chooseFriend, friendId])
        }
    }

    return (
        <>
            <Button icon={icon} onClick={onClickShowModal}>
                {
                    valueType == 1 ? (
                        <span>Công khai</span>
                    ) : valueType == 2 ? (
                        <span>Bạn bè</span>
                    ) : (
                        <span>Chỉ mình tôi</span>
                    )
                }
            </Button>
            <Modal className='modal_type' title="Chọn đối tượng" visible={isModalVisible} onOk={handleOk} onCancel={handleCancel}>
                <Radio.Group className='w-100' onChange={onChange} value={type}>
                    <Space direction="vertical">
                        <Radio value={1}>
                            <span className='mr-2 icon_type'><i className="fas fa-globe-asia"></i></span>
                            <span>Công khai</span>
                        </Radio>
                        <Radio value={2}>
                            <span className='mr-2 icon_type'><i className="fas fa-user-friends"></i></span>
                            <span>Bạn bè</span>
                        </Radio>
                        <Radio value={3}>
                            <span className='mr-2 icon_type'><i className="fas fa-lock"></i></span>
                            <span>Chỉ mình tôi</span>
                        </Radio>
                    </Space>
                </Radio.Group>
            </Modal>
            <Modal className='choose_friend' title="Bạn bè cụ thể" 
                visible={isModalChooseFriendVisible} 
                onCancel={handleChooseFriendCancel}
                footer={[
                    <Button key={'submit'} onClick={handleChooseFriendCancel} type="primary">Lưu</Button>
                ]}
            >
                <Input placeholder="Tìm kiếm bạn bè" prefix={<SearchOutlined />} allowClear onChange={(e) => setSearchFriend(e.target.value)}/>
                <h3 className='mt-2 mb-1 pl-1'>Bạn bè</h3>
                <div className="list_friend">
                    {
                        listFriend.filter((val) => {
                            return (
                                val.user_name.toLowerCase().includes(searchFriend.toLocaleLowerCase())
                            )
                        }).map((friend) => (
                            <div key={friend.id} className='row cursor_pointer friend' onClick={(e) => handleChooseFriend(friend.id_chat)}>
                                <div className="col-11 pl-1">
                                    <img className='image_profile' src={friend.avatar} alt="" width={'35px'} height="35px"/>
                                    <span className='ml-2'>{ friend.user_name }</span>
                                </div>
                                <div className="col-1 pr-1 text-right check">
                                    {
                                        chooseFriend.includes(friend.id_chat) ? (
                                            <CheckCircleFilled />
                                        ) : (
                                            <CheckCircleOutlined />
                                        )
                                    }
                                </div>
                            </div>
                        ))
                    }
                </div>
            </Modal>
        </>
        
    );
};

export default ModalType;