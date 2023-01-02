import ReactDOM from "react-dom";
import React from "react";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import EnterPin from "./EnterPin";
import Quiz from "./Quiz";
import Identify from "./Identify";
import Wait from "./Wait";
import Lobby from "./Lobby";

const AppGame = () => {
    return (
        <>
            <Router>
                <Routes>
                    <Route path="/game" element={<EnterPin />} />
                    <Route path="/game/identify" element={<Identify />} />
                    <Route path="/game/wait" element={<Wait />} />
                    <Route path="/game/lobby/:quiz_id" element={<Lobby />} />
                    <Route path="/game/quiz/:quiz_id" element={<Quiz />} />
                </Routes>
            </Router>
        </>
    );
};

export default AppGame;

if (document.getElementById("react")) {
    ReactDOM.render(<AppGame />, document.getElementById("react"));
}
