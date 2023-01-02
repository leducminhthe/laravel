import React, { useState, useEffect } from 'react';
import ContactInfo from './about/ContactInfo';
import FamilyRelationship from './about/FamilyRelationship';
import Overview from './about/Overview';
import PlaceLive from './about/PlaceLive';
import StudyWork from './about/StudyWork';

const InfoAbout = ({ auth, listFriend }) => {
    const [type, setType] = useState(1);
    return (
        <div className="row mx-4 wrraped_content_option">
            <div className="col-5 pl-1 content content_about_left">
                <h3 className='p-3 mt-2 mb-0'><strong>Giới thiệu</strong></h3>
                <ul className='pb-2'>
                    <li className={`cursor_pointer ${type == 1 && 'active'}`} onClick={(e) => setType(1)}>Tổng quan</li>
                    <li className={`cursor_pointer ${type == 2 && 'active'}`} onClick={(e) => setType(2)}>Công việc và học vấn</li>
                    <li className={`cursor_pointer ${type == 3 && 'active'}`} onClick={(e) => setType(3)}>Nơi từng sống</li>
                    <li className={`cursor_pointer ${type == 4 && 'active'}`} onClick={(e) => setType(4)}>Thông tin liên hệ và cơ bản</li>
                    <li className={`cursor_pointer ${type == 5 && 'active'}`} onClick={(e) => setType(5)}>Gia đình và các mối quan hệ</li>
                </ul>
            </div>
            <div className="col-7 content_about_right">
            {(() => {
                if (type == 1) {
                    return (
                        <Overview auth={auth} listFriend={listFriend}/>
                    )
                } else if (type == 2) {
                    return (
                        <StudyWork />
                    )
                } else if (type == 3) {
                    return (
                        <PlaceLive />
                    )
                } else if (type == 4) {
                    return (
                        <ContactInfo />
                    )
                } else if (type == 5) {
                    return (
                        <FamilyRelationship listFriend={listFriend}/>
                    )
                }
            })()}
            </div>
        </div>
    );
};

export default InfoAbout;