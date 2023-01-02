import React, { useRef } from "react";
import { Redirect, useNavigate } from "react-router-dom";
import { Row } from "antd";
import Axios from "axios";
import serialize from "form-serialize";
const Identify = () => {
    const navigate = useNavigate();
    const inputRef = useRef();

    const handleSubmit = (e) => {
        e.preventDefault();
        const data = serialize(e.currentTarget);
        Axios.post(` /game/start`, data).then((response) => {
            // console.log(response.data);
            navigate(`/game/wait`, { state: { name: inputRef.current.value } });
        });
    };
    return (
        <div className="identify">
            <div className="ELhfo"></div>
            <div className="jwZLlr"></div>
            <div className="content">
                <Row
                    type="flex"
                    justify="center"
                    align="middle"
                    style={{ minHeight: "100vh" }}
                >
                    <form onSubmit={handleSubmit}>
                        <div className="ant-row ant-form-item">
                            <input
                                ref={inputRef}
                                type="text"
                                className="ant-input ant-input-lg"
                                name="pin"
                                placeholder="Nhập Tên"
                            />
                        </div>
                        <div className="ant-row ant-form-item">
                            <button
                                type="submit"
                                className="ant-btn ant-btn-primary ant-btn-lg"
                            >
                                Enter
                            </button>
                        </div>
                    </form>
                </Row>
            </div>
        </div>
    );
};
export default Identify;
