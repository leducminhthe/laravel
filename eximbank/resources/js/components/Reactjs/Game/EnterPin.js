import React from "react";
import { useNavigate } from "react-router-dom";
import Axios from "axios";
import serialize from "form-serialize";
import { Row } from "antd";

const EnterPin = () => {
    const navigator = useNavigate();
    const handleSubmit = async (e) => {
        e.preventDefault();
        const form = e.currentTarget;
        const body = serialize(form, { hash: true, empty: true });
        console.log(body);
        await Axios.post(`/game/pin`, body).then((response) => {
            navigator(`/game/identify`);
        });
    };
    return (
        <div className="enterpin">
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
                                type="text"
                                className="ant-input ant-input-lg"
                                name="pin"
                                placeholder="Nhập mã pin"
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
export default EnterPin;
