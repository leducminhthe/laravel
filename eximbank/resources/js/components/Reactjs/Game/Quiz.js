import Axios from "axios";
import React from "react";
import { useParams } from "react-router-dom";
const Quiz = () => {
    const { quiz_id } = useParams();
    const fetchData = () => {
        Axios.get(`/game/lobby/${quiz_id}`).then((respone) => {
            console.log(respone.data);
        });
    };
    return <div className="quiz">Trang thi</div>;
};
export default Quiz;
