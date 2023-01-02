import React from 'react';
import { Link } from 'react-router-dom';    

const ImagePost = ({ images, idPost }) => {
    return (
        <div className='row position_image'>
            <div className="col-12">
            {
                images.length == 1 && (
                    <div  className="row m-0">
                        {
                            images.map((item, key) => (
                                <Link to={`/social-network/detail/photo/${idPost}/${item.id}`}>
                                    <img key={key} src={item.image} alt="" width={'100%'}/>
                                </Link>
                            ))
                        }
                    </div>
                )
            }
            {
                images.length == 2 && (
                    <div className="row">
                        {
                            images.map((item, key) => (
                                <div key={key} className="col-12">
                                    <Link to={`/social-network/detail/photo/${idPost}/${item.id}`}>
                                        <img src={item.image} alt="" width={'100%'} height="200px"/>
                                    </Link>
                                </div>
                            ))
                        }
                    </div>
                )
            }
            {
                images.length == 3 && (
                    <div className="row">
                        <div className="col-12">
                            <Link to={`/social-network/detail/photo/${idPost}/${images[0].id}`}>
                                <img src={images[0].image} alt="" width={'100%'} height="200px"/>
                            </Link>
                        </div>
                        <div className="col-6 pr-0">
                            <Link to={`/social-network/detail/photo/${idPost}/${images[1].id}`}>
                                <img src={images[1].image} alt="" width={'100%'} />
                            </Link>
                        </div>
                        <div className="col-6 pl-0">
                            <Link to={`/social-network/detail/photo/${idPost}/${images[2].id}`}>
                                <img src={images[2].image} alt="" width={'100%'}/>
                            </Link>
                        </div>
                    </div>
                )
            }
            {
                images.length == 4 && (
                    <div className="row">
                        <div className="col-12">
                            <Link to={`/social-network/detail/photo/${idPost}/${images[0].id}`}>
                                <img src={images[0].image} alt="" width={'100%'} height="180px"/>
                            </Link>
                        </div>
                        <div className="col-4 pr-0">
                            <Link to={`/social-network/detail/photo/${idPost}/${images[1].id}`}>
                                <img src={images[1].image} alt="" width={'100%'} height="110px"/>
                            </Link>
                        </div>
                        <div className="col-4 px-1">
                            <Link to={`/social-network/detail/photo/${idPost}/${images[2].id}`}>
                                <img src={images[2].image} alt="" width={'100%'} height="110px"/>
                            </Link>
                        </div>
                        <div className="col-4 pl-0">
                            <Link to={`/social-network/detail/photo/${idPost}/${images[3].id}`}>
                                <img src={images[3].image} alt="" width={'100%'} height="110px"/>
                            </Link>
                        </div>
                    </div>
                )
            }
            {
                images.length > 4 && (
                    <div className="row">
                        <div className="col-12">
                            <Link to={`/social-network/detail/photo/${idPost}/${images[1].id}`}>
                                <img src={images[0].image} alt="" width={'100%'} height="180px"/>
                            </Link>
                        </div>
                        <div className="col-4 pr-0">
                            <Link to={`/social-network/detail/photo/${idPost}/${images[2].id}`}>
                                <img src={images[1].image} alt="" width={'100%'} height="110px"/>
                            </Link>
                        </div>
                        <div className="col-4 px-1">
                            <Link to={`/social-network/detail/photo/${idPost}/${images[3].id}`}>
                                <img src={images[2].image} alt="" width={'100%'} height="110px"/>
                            </Link>
                        </div>
                        <div className="col-4 last_image pl-0">
                            <Link to={`/social-network/detail/photo/${idPost}/${images[4].id}`}>
                                <img src={images[3].image} alt="" width={'100%'} height="110px"/>
                            </Link>
                            <h1 className='count_rest'>+{ images.length - 4 }</h1>
                        </div>
                    </div>
                )
            }
            </div>
        </div>
    );
};

export default ImagePost;