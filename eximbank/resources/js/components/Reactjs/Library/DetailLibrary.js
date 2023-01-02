import React from 'react';
import { useParams } from 'react-router-dom';    
import AudioBookDetail from '../DetailLibraryType/AudioBookDetail';
import BookDetail from '../DetailLibraryType/BookDetail';
import EbookDetail from '../DetailLibraryType/EbookDetail';
import VideoDetail from '../DetailLibraryType/VideoDetail';

const DetailLibrary = ({ text }) => {
    const { type } = useParams();

    var nameType;
    if (type == 1) {
        nameType = text.book;
    } else if (type == 2) {
        nameType = text.ebook;
    } else if (type == 3) {
        nameType = text.document;
    } else if (type == 4) {
        nameType = "Video";
    } else {
        nameType = text.audiobook;
    }

    return (
        <>
            {(() => {
                if (type == 1) {
                    return (
                        <BookDetail nameType={nameType} type={type} text={text}/>
                    )
                } else if (type == 2 || type == 3) {
                    return (
                        <EbookDetail nameType={nameType} type={type} text={text}/>
                    )
                } else if (type == 5) {
                    return (
                        <AudioBookDetail nameType={nameType} type={type} text={text}/>
                    )
                } else {
                    return (
                        <VideoDetail nameType={nameType} type={type} text={text}/>
                    )
                }
            })()}
        </>
    );
};

export default DetailLibrary;