/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

require("./bootstrapReact");
import "antd/dist/antd.css";

var url = window.location.href;
switch (url.split("/")[3]) {
    case "game":
        require("./components/Reactjs/Game/AppGame");
        break;
    case "library":
        require("./components/Reactjs/Library/Example");
        break;
    case "survey-react":
        require("./components/Reactjs/Survey/AppSurvey");
        break;
    case "suggest-react":
        require("./components/Reactjs/Suggest/AppSuggest");
        break;
    case "note-react":
        require("./components/Reactjs/Note/AppNote");
        break;
    case "promotion-react":
        require("./components/Reactjs/Promotion/AppPromotion");
        break;
    case "faq-react":
        require("./components/Reactjs/Faq/AppFaq");
        break;
    case "topic-situation-react":
        require("./components/Reactjs/TopicSituation/AppTopicSituation");
        break;
    case "guide-react":
        require("./components/Reactjs/Guide/AppGuide");
        break;
    case "forums-react":
        require("./components/Reactjs/Forum/AppForum");
        break;
    case "daily-training-react":
        require("./components/Reactjs/DailyTraining/AppDailyTraining");
        break;
    case "quiz-react":
        require("./components/Reactjs/Quiz/AppQuiz");
        break;
    case "news-react":
        require("./components/Reactjs/News/AppNews");
        break;
    case "course-react":
        require("./components/Reactjs/Course/AppCourse");
        break;
    case "social-network":
        require("./components/Reactjs/SocialNetwork/AppSocialNetwork");
        break;
    default:
        break;
}
