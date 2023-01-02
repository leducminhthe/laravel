import Axios from "axios";
import React from "react";
import { useParams, useNavigate } from "react-router-dom";
const Lobby = () => {
    const { quiz_id } = useParams();
    const Navigate = useNavigate();
    const fetchData = () => {
        Axios.get(`/game/lobby/${quiz_id}`).then((respone) => {
            console.log(respone.data);
        });
    };
    const handleClick = () => {
        Axios.post(`/game/quiz/${quiz_id}`).then((respone) => {
            console.log(respone.data);
            Navigate(`/game/quiz/${quiz_id}`);
        });
    };
    return (
        <div className="enterpin">
            <div className="ELhfo"></div>
            <div className="jwZLlr"></div>
            <div className="content">
                <div className="header">
                    <div className="header_game_wrapper">
                        <div className="header_game_wrapper_content">
                            <div className="header_game_pin_left">
                                <div>
                                    Join at <strong>www.kahoot.it</strong>
                                </div>
                                <div>
                                    or with the <strong>Kahoot! app</strong>
                                </div>
                            </div>
                            <div className="header_game_pin_center">
                                <div className="header_game_pin_center_label">
                                    Mã PIN:{" "}
                                </div>
                                <div className="header_game_pin_center_pin">
                                    <button>
                                        <span>423</span>
                                        <span>5618</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button aria-expanded="false">
                                <span>
                                    <title id="label-bc0fa20f-ab09-4a36-9954-626848ed08af">
                                        Icon
                                    </title>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div className="lobbystyles_wrapper">
                    <div className="lobbystyles_item">
                        <div className="player_count_wrapper">
                            <span className="icon"></span>
                            <div className="player_counter_text_wrapper">
                                <div className="player_counter_Text">0</div>
                            </div>
                            <div className="player_counter_hidden_padding">
                                00
                            </div>
                        </div>
                    </div>
                    <div className="lobbystyles_item lobbystyles_item_center">
                        <div aria-label="Kahoot!">
                            <span>Kahoot!</span>
                        </div>
                    </div>
                    <div className="lobbystyles_item">
                        <div className="lobbystyles_start_wrapper">
                            <div className="lock_button_wrapper">
                                <div aria-expanded="false">
                                    <div className="lock_button_LockButton">
                                        <button className="sc-bZSQDF fDzbQy">
                                            <span className="sc-dlfnbm idokwv"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div aria-expanded="false">
                                <button
                                    className="enter_button"
                                    onClick={handleClick}
                                >
                                    Start
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <section className="lobbystyles__section">
                    <ul className="controller_list"></ul>
                    <div
                        data-functional-selector="waiting-players"
                        className="lobbystyles__WaitingMessageWrapper"
                    >
                        Waiting for players…
                    </div>
                </section>
            </div>
        </div>
    );
};
export default Lobby;
