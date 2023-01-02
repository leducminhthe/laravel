import ReactDOM from 'react-dom';
import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'
import SocialNetwork from './SocialNetwork';
import Axios from 'axios';
import MenuSocialNetWork from './MenuSocialNetWork';
import Info from './Info';
import DetailPostPhoto from './DetailPostPhoto';
import Friends from './friends/Friends';
import Videos from './video/Videos';
import Groups from './group/Groups';

const AppSocialNetwork = () => {
    const [auth, setAuth] = useState('');
    const [listFriend, setListFriend] = useState([]);

    const fetchDataListFriend = async () => {
        try {
            const items = await Axios.get(`/data-list-friend/${auth.user_id}`)
            .then((response) => {
                setListFriend(response.data.list_friends.data)
            })
        } catch (error) {
            console.error("Error: " + error.message);
        }
    }

    useEffect(() => {
        if (localStorage.getItem("auth") != null) {
            var get_auth = localStorage.getItem("auth");
            setAuth(JSON.parse(get_auth))
        } else {
            const fetchDataAuth = async () => {
                try {
                    const items = await Axios.get(`/data-auth`)
                    .then((response) => {
                        setAuth(response.data.profile)
                        localStorage.setItem("auth", JSON.stringify(response.data.profile))
                    })
                } catch (error) {
                    console.error("Error: " + error.message);
                }
            }
            fetchDataAuth();
        }
        
    }, []);

    useEffect(() => {
        if(auth) {
            fetchDataListFriend()
        }
    }, [auth])
    
    return(
        <>
            <Router>
                <div className='row m-0' id='social_home'>
                    <MenuSocialNetWork auth={auth}/>
                    <Routes>
                        <Route path='/social-network' element={<SocialNetwork auth={auth} listFriend={listFriend}/>}/>
                        <Route path='/social-network/info/:userId' element={<Info auth={auth} listFriend={listFriend}/>} />
                        <Route path='/social-network/friends' element={<Friends auth={auth} listFriend={listFriend}/>} />
                        <Route path='/social-network/detail/photo/:id/:idImage' element={<DetailPostPhoto auth={auth}/>} />
                        <Route path='/social-network/videos' element={<Videos auth={auth}/>} />
                        <Route path='/social-network/groups' element={<Groups auth={auth}/>} />
                    </Routes>
                </div>
            </Router>
        </>
    )
}

export default AppSocialNetwork

if (document.getElementById('react')) {
    const element = document.getElementById('link_image')
    const text = Object.assign({}, element.dataset)
    ReactDOM.render(<AppSocialNetwork {...text}/>, document.getElementById('react'));
} 
